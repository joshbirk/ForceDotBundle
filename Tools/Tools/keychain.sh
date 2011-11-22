security 2>&1 >/dev/null find-generic-password -ga $1 \
|ruby -e 'print $1 if STDIN.gets =~ /^password: "(.*)"$/'