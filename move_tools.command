DIR="$( cd "$( dirname "$0" )" && pwd )"
cd $DIR
if [! -f ~/Library/Application\ Support/TextMate/Bundles/ForceDotBundle.tmbundle ]
	then
	mkdir ~/Library/Application\ Support/TextMate/Bundles/ForceDotBundle.tmbundle
fi
cp -R Tools ~/Library/Application\ Support/TextMate/Bundles/ForceDotBundle.tmbundle/Tools
chmod -R 775 ~/Library/Application\ Support/TextMate/Bundles/ForceDotBundle.tmbundle/Tools
cd ..
mv ForceDotBundle ForceDotBundle.tmBundle
mate ForceDotBundle.tmBundle
