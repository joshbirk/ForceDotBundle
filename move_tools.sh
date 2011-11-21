mkdir ~/Library/Application\ Support/TextMate/Bundles/ForceDotBundle.tmbundle
cp -R Tools ~/Library/Application\ Support/TextMate/Bundles/ForceDotBundle.tmbundle/Tools
chmod -R 775 ~/Library/Application\ Support/TextMate/Bundles/ForceDotBundle.tmbundle/Tools
cd ..
mv ForceDotBundle ForceDotBundle.tmBundle
mate ForceDotBundle.tmBundle