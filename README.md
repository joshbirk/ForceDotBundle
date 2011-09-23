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
Once you have that, you can download these files, rename the parent file  "ForceDotCom.tmbundle", then double click that file to install. 
You can also update the bundle in the same way.
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


<P>
<H1>Quick Start</H1>
<UL>
	<LI>Once you've installed the bundle, create a new project.
	<LI>Create a new directory in Finder, name it something reasonable for your project.
	<LI>Drag the folder to the project pane in TextMate.
	<LI>Create an new empty file.  Any one will do.  We'll be deleting it soon anyway.
	<LI>Open the empty file.  Now use "Create New Project" from the ForceDotCom bundle.  A popup will give the overview of the changes. <B>These changes may not appear right away in the project pane</B>.  They will be in Finder.
	<LI>Update "build.properties" with your username, password and if needed, the login URL (for sandboxes).
	<LI>If you want to pull down existing files, run "Get Latest". <B>These changes may not appear right away in the project pane</B>.  They will be in Finder.
	<LI>Or if you just want to be additive, right click on say, classes, and use one of the templates.
	<LI>Enjoy.
</UL>
</P>
		
		
	
</P>
