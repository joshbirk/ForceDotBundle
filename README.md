<h1>ForceDotBundle</h1>
This project is the end result of cleaning up some shell scripts I had been tinkering with
for using with the Force.com Migration Tool and the OS X editor TextMate.  They could be 
potentially be used as standalone shell scripts, but I'll gather those in a different project.

I've got videos on using the bundle one YouTube: <a href="http://www.youtube.com/joshuabirk">http://www.youtube.com/joshuabirk</a>.

<h2>Requirements</h2>
You will need to download and setup the Force.com Migration Tool, which is fairly straightforward 
in OS X.  Just go to "Setup -> Develop -> Tools" in your org and download the zip and follow the 
instructions.  You might need to run "ant -diagnostics" to find your ant lib directory.

<h2>Installation from Finder</h2>
Fork/download/clone the project.   Make sure the resulting folder is named "ForceDotBundle".  Go into that folder and 
double click "move_tools.command" (icon looks like a document with a black box).  If you have the CLI command 
for TextMate installed, you should just see the bundle loaded up.  Otherwise double click the now renamed parent directory.

<h2>Installation from Shell</h2>
There is a shell script "move_tools.sh" now. Make sure the resulting folder is named "ForceDotBundle".  Run that script and it should move the Tools directory into TextMate, rename the parent directory to have ".tmBundle" and if you have the mate CLI command installed, install it into TextMate.  Otherwise, double click on the now renamed directory.

<h2>Manual Installation</h2>
If the move_tools.sh file won't run for some reason, you can replicate the steps by:
<OL>
 <LI>Downloading or forking the project to a local folder.	
 <LI>Go into the folder, copy the "Tools" directory.
 <LI>Go back to the parent folder.  Rename "ForceDotBundle" (or whatever the result parent dir was) to "ForceDotBundle.tmBundle".
 <LI>Double click the renamed directory.  TextMate should run and confirm it was installed.
 <LI>Go to "~/Library/Application Support/TextMate/Bundles" (not this is off your user directory).
 <LI>Right click on "ForceDotCom.tmBundle" and select "Show Package Contents".
 <LI>Paste "Tools" into the now opened package.
 <LI>Now to Terminal and run the following: 
	<pre>
	chmod -R 775 ~/Library/Application\ Support/TextMate/Bundles/ForceDotCom.tmbundle/Tools
	</pre>	
</OL>

<h2>Known Bugs</H2>
It seems the first time you use one of the REST operations, the temporary data.json file isn't getting seen correctly.  Should run correctly 
subsequently.  Looking into it.	

<P>
<B>Note:</B> This is unofficial, unsupported software.
</P>	

<h2>Features</h2>	
<P>
The bundle provides the following:
<UL>
<LI>Languages
	<OL>
		<LI>Apex
		<LI>Visualforce
	</OL>
<LI>Templates	
	<OL>
		<LI>Apex Class
		<LI>Apex Trigger
		<LI>Visualforce Page
		<LI>Visualforce Trigger
	</OL>
<LI>Commands
	<OL>
		<LI>Create Project
		<UL>
			<LI>Start New Project: this command will generate a skeleton directory structure, a tmp directory and dummy build files (build.properties, package.xml, build.xml).  Update build.properties to associate the project with your org.
			<LI>Get Latest: this will bring down all the code and components from the org
		</UL>
		<LI>Build Files
		<UL>
			<LI>Build File: this will build the current file being edited
			<LI>Build All: this will build all the files in the project
		</UL>
		<LI>Run Tests
		<UL>
			<LI>Run Tests: this will run all tests against the org
			<LI>Run Current Tests: this will run the tests on the currently open file
		</UL>
		<LI>REST Operations
		<UL>
			<LI>Get Object: Opens an HTML window describing the fields and child relationships of an object defined by the currently highlighted text.
			<LI>Run SOQL: Opens an HTML window with the results of the SOQL query in the currently highlighted text.
			<LI><I>Note: you need the pod variable defined in build.properties for this to work</I>
		</UL>
		<LI>Bookmarks
		<UL>
			<LI>Go To Visualforce Page: Opens the VF page of either the currently open document or highlighted text
			<LI>Go To Record: Opens the page corresponding to the ID of the currently highlighted text
			<LI>Execute/Debug Apex: Opens the system log
			<LI>New Custom Object: Opens the Custom Object wizard
			<LI>Custom Labels: Opens the Custom Labels page
			<LI>New Debug Log: Opens the page to add a new debug log/user
			<LI>Debug Logs: Opens the Debug Logs page
			<LI>Apex Reference: Opens the Apex reference documentation
			<LI>Visualforce Reference: Opens the VF component reference documentation
			<LI>Logout: Logs out the current web session
			<LI><I>Note: you need the pod variable defined in build.properties for this to work</I>
		</UL>
		<LI>Deploy To Production
		<UL>
			<LI>Production Test: This will do a check only, run all tests, deploy against the production org
			<LI>Production Deploy: This will run all tests and deploy against the production org
		</UL>
		<LI>Keychain
		<UL>
			<LI>Add Password: Highlighted text in the format "password:accountname" will add a new password to Keychain under accountname and replace the text with accountname.
			<LI>Lock Keychain: Locks keychain and requires a password for later use.
		</UL>
	</OL>
</UL>
</P>


<P>
<H1>Quick Start</H1>
<OL>
	<LI>Once you've installed the bundle, create a new project.
	<LI>Create a new directory in Finder, name it something reasonable for your project.
	<LI>Drag the folder to the project pane in TextMate.
	<LI>Create some kind of scratch file.
	<LI>Open the empty file.  Now use "Create New Project" from the ForceDotCom bundle.  A popup will give the overview of the changes. <B>These changes may not appear right away in the project pane</B>.  They will be in Finder.
	<LI>Update "build.properties" with your username, password and if needed, the login URL (for sandboxes).
	<LI>If you want to pull down existing files, run "Get Latest". <B>These changes may not appear right away in the project pane</B>.  They will be in Finder.
	<LI>Or if you just want to be additive, right click on say, classes, and use one of the templates.
	<LI>Enjoy.
</OL>
</P>
<P>
<H1>Using Keychain</H1>
For better production, you can now store your passwords in OS X Keychain and refer only to the Keychain account name in build.properties.  
<OL>
	<LI>Make sure "usekeychainaccess" is set to "enabled" in build.properties.
	<LI>Set "{realpassword}:{keychainaccountname}" as the password.
	<LI>Go to Bundles->ForceDotCom->Keychain Access->Add Password.
	<LI>If successful, the text will be replaced with just the {keychainaccountname}.
</OL>
<P>
<H1>Using REST and HTML bookmarks</H1>
Set "pod=" in build.properties to match the beginning of the URL in your Salesforce org domain.  For instance, if after you login you see "https://na8.salesforce.com" - set it to be "pod=na8".
</P>		
		
	
</P>
