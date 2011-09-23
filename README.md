<h1>ForceDotBundle</h1>
This project is the end result of cleaning up some shell scripts I had been tinkering with
for using with the Force.com Migration Tool and the OS X editor TextMate.  They could be 
potentially be used as standalone shell scripts, but I'll gather those in a different project.

<P>
You will need to download and setup the Force.com Migration Tool, which is fairly straightforward 
in OS X.  Just go to "Setup -> Develop -> Tools" in your org and download the zip and follow the 
instructions.  You might need to run "ant -diagnostics" to find your ant lib directory.
</P>

<P>
Once you have that, you can download these files, zip the parent folder and rename the extension to "tmbundle".
Then move that file to "~/Library/Application Support/Textmate/Bundles".  You can either restart TextMate or 
go to the Bundle Editor and use "Reload Bundles".  A ForceDotCom bundle should appear.
<P>
	
	
<P>
The bundle provides the following:
<UL>
<LI>Languages
	<OL>
		<LI>Apex
		<LI>Visualforce
	</OL>
	<I>I need to work on the highlighting for both of these.</I>
<LI>Templates	
	<OL>
		<LI>Apex Class
		<LI>Apex Trigger
		<LI>Visualforce Page
		<LI>Visualforce Trigger
	</OL>
<LI>Commands
	<OL>
		<LI>Start New Project: this command will generate a skeleton directory structure, a tmp directory and dummy build files (build.properties, package.xml, build.xml).  Update build.properties to associate the project with your org.
		<LI>Get Latest: this will bring down all the code and components from the org
		<LI>Build File: this will build the current file being edited
		<LI>Build All: this will build all the files in the project
		<LI>Run Tests: this will run all tests against the org
	</OL>
</UL>
</P>