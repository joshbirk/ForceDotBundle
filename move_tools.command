DIR="$( cd "$( dirname "$0" )" && pwd )"
cd $DIR
if [! -f ~/Library/Application\ Support/TextMate/Bundles/ForceDotCom.tmbundle ]
	then
	mkdir ~/Library/Application\ Support/TextMate/Bundles/ForceDotCom.tmbundle
fi
cp -R Tools ~/Library/Application\ Support/TextMate/Bundles/ForceDotCom.tmbundle/Tools
chmod -R 775 ~/Library/Application\ Support/TextMate/Bundles/ForceDotCom.tmbundle/Tools
cd ..
mv ForceDotBundle ForceDotBundle.tmBundle
mate ForceDotBundle.tmBundle
