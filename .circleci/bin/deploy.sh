#!/usr/bin/env bash

BUILD_REPO=git@github.com:peterwilsoncc/built.peterwilson.cc.git
BUILD_DIR=$UNICI_TMPDIR/build
GIT_USER="${DEPLOY_GIT_USER:-CircleCI}"
GIT_EMAIL="${DEPLOY_GIT_EMAIL:-wilson+circleci-build@peterwilson.cc}"
DEPLOY_BRANCH="${CIRCLE_BRANCH}"
SRC_DIR="${UNICI_PROJECT_DIRECTORY}"
COMMIT=$(git rev-parse HEAD)

source "$UNICI_PROJECT_DIRECTORY/.circleci/bin/install-functions.sh"

download_wp_core
version_plugin "$UNICI_PROJECT_DIRECTORY/mu-plugins/pwcc-helpers";
version_plugin "$UNICI_PROJECT_DIRECTORY/mu-plugins/pwcc-multi-domain";
version_plugin "$UNICI_PROJECT_DIRECTORY/mu-plugins/pwcc-notes";
version_plugin "$UNICI_PROJECT_DIRECTORY/mu-plugins/pwcc-code-bin";
version_plugin "$UNICI_PROJECT_DIRECTORY/themes/pwcc-003" "style.css";

if [[ -z "$DEPLOY_BRANCH" ]]; then
	echo "No branch specified!"
	exit 1
fi

if [[ -d "$BUILD_DIR" ]]; then
	echo "WARNING: ${BUILD_DIR} already exists. You may have accidentally cached this"
	echo "directory. This will cause issues with deploying."
	exit 1
fi

# Ensure submodules are up-to-date.
git submodule update --init --recursive

# Build assets.
yarn build

# Create and change to build dir.
mkdir -p $BUILD_DIR
cd $BUILD_DIR

# Checkout Build repo's branch
git init
git remote add origin $BUILD_REPO;
git fetch

# If the deploy branch doesn't already exist, create it from the empty root.
if ! git rev-parse --verify "remotes/origin/$DEPLOY_BRANCH" >/dev/null 2>&1; then
	echo -e "\nCreating $DEPLOY_BRANCH..."
	git checkout -b "$DEPLOY_BRANCH"
else
	echo "Using existing $DEPLOY_BRANCH"
	git checkout -b "$DEPLOY_BRANCH" "origin/$DEPLOY_BRANCH"
fi

# Remove existing files
git rm -rfq .

# Sync built files
echo -e "\nSyncing files..."
if ! command -v 'rsync'; then
	sudo apt-get update
	sudo apt-get install -q -y rsync
fi

# Copy the production root files.
rsync -av "$SRC_DIR/.circleci/prod-root/" "$BUILD_DIR" --exclude-from "$SRC_DIR/.circleci/deploy-exclude.txt"

# Don't forget WordPress
mkdir -p $BUILD_DIR/wp
rsync -av "$WP_CORE_DIR/" "$BUILD_DIR/wp"

# And the content directory.
mkdir -p $BUILD_DIR/content
rsync -av "$SRC_DIR/" "$BUILD_DIR/content" --exclude-from "$SRC_DIR/.circleci/deploy-exclude.txt"
rm $BUILD_DIR/content/.gitignore

# Add changed files
git add .

if [ -z "$(git status --porcelain)" ]; then
	echo "No changes to built files."
	exit
fi

# Print status!
echo -e "\nSynced files. Changed:"
git status -s

# Double-check our user/email config
if ! git config user.email; then
	git config user.name "$GIT_USER"
	git config user.email "$GIT_EMAIL"
fi

# Commit it.
MESSAGE=$( printf 'Build changes from %s\n\n%s' "${COMMIT}" "${CIRCLE_BUILD_URL}" )
git commit -m "$MESSAGE"

# Push it (real good).
git push origin "$DEPLOY_BRANCH"
