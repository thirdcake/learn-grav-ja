#!/bin/bash
# bash lastmod.sh > ./php/sitemap.json
echo '{'
first=true
git ls-files | grep '^src/pages/.*/index\.md$' | while read f; do
  if [ "$first" = true ]; then
    first=false
  else
    echo ','
  fi
  date=$(git log -1 --format=%cd --date=format:'%Y-%m-%d' -- "$f")
  echo "\"$f\": \"$date\""
done
echo '}'
