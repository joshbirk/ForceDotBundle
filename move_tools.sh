mkdir ~/Library/Application\ Support/TextMate/Bundles/ForceDotCom.tmbundle
cp -R Tools ~/Library/Application\ Support/TextMate/Bundles/ForceDotCom.tmbundle/Tools
chmod -R 775 ~/Library/Application\ Support/TextMate/Bundles/ForceDotCom.tmbundle/Tools
cd ..
mv ForceDotBundle ForceDotBundle.tmBundle
mate ForceDotBundle.tmBundle