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
git submodule update --init

# Create and change to build dir.
mkdir -p $BUILD_DIR/content
mkdir -p $BUILD_DIR/wp
cd $BUILD_DIR

# Checkout Build repo's branch
git init
git remote add origin $BUILD_REPO;

# If the deploy branch doesn't already exist, create it from the empty root.
if ! git rev-parse --verify "remotes/origin/$DEPLOY_BRANCH" >/dev/null 2>&1; then
	echo -e "\nCreating $DEPLOY_BRANCH..."
	git checkout -b "$DEPLOY_BRANCH"
else
	echo "Using existing $DEPLOY_BRANCH"
	git checkout "$DEPLOY_BRANCH" "origin/$DEPLOY_BRANCH"
fi

# Sync built files
echo -e "\nSyncing files..."
if ! command -v 'rsync'; then
	sudo apt-get install -q -y rsync
fi

# Don't forget WordPress
rsync -av "$WP_CORE_DIR" "$BUILD_DIR/wp"

# And the production root files.
rsync -av "$SRC_DIR/.circleci/prod-root" "$BUILD_DIR" --exclude-from "$SRC_DIR/.circleci/deploy-exclude.txt"

# And the content directory.
rsync -av "$SRC_DIR/" "$BUILD_DIR/content" --exclude-from "$SRC_DIR/.circleci/deploy-exclude.txt"

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
