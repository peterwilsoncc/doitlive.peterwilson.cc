#!/bin/bash
git diff --summary | grep --color 'mode change 100755 => 100644' | cut -d' ' -f7- | xargs chmod +x
git diff --summary | grep --color 'mode change 100644 => 100755' | cut -d' ' -f7- | xargs chmod -x
