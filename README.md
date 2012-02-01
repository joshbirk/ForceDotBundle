<h1>ForceDotBundle</h1>
This project is the end result of cleaning up some shell scripts I had been tinkering with
for using with the Force.com Migration Tool and the OS X editor TextMate.  I've recently expanded it to compile directly to the API, but still use the Migration Tool for getting projects and compiling entire projects.  All of the "old" build paths are there, but I generally use Quick Compile 99% of the time at home.

I've got videos on using the bundle on YouTube: http://www.youtube.com/joshuabirk

You may also wish to check out MavensMate, a ForceDotCom bundle for TextMate created by Mavens Software.  It has some extremely pretty interfaces and some great functionality: https://github.com/joeferraro/MavensMate

<B>This is unofficial software and unsupported as well</B>.  I've been able to keep up with updates and bug fixes so far, but make no promises.

<h2>Requirements</h2>
You will need to download and setup the Force.com Migration Tool, which is fairly straightforward 
in OS X.  Just go to "Setup -> Develop -> Tools" in your org and download the zip and follow the 
instructions.  You might need to run "ant -diagnostics" to find your ant lib directory.

<h2>Installation from Downloads Page</h2>
If you go to the Downloads page, download "ForceDotBundle_beta.zip". Double click "ForceDotBundle.tmBundle" after unarchiving.

<h2>Installation from Finder</h2>
Fork/download/clone the project. Rename the parent folder "ForceDotBundle.tmBundle" and double click it. 

<P>
<H1>Quick Start</H1>
<OL>
	<LI>Once you've installed the bundle, create a new project.
	<LI>Create a new directory in Finder, name it something reasonable for your project.
	<LI>Drag the folder to the project pane in TextMate.
	<LI>Create some kind of scratch file.
	<LI>Open the empty file.  Now use "Create New Project" from the ForceDotBundle bundle.  A popup will give the overview of the changes. <B>These changes may not appear right away in the project pane</B>.  They will be in Finder.
	<LI>Update "build.properties" with your username, password and if needed, the login URL (for sandboxes).
	<LI>If you want to pull down existing files, run "Get Latest". <B>These changes may not appear right away in the project pane</B>.  They will be in Finder.
	<LI>Or if you just want to be additive, right click on say, classes, and use one of the templates.
	<LI>Enjoy.
	<LI>You may need to whitelist your IP for some of the features.
</OL>
</P>	

<H2>Enabling Keychain Support</H2>
ForceDotBundle will swap out the password in build.properties with secured text in OS X's keychain if you have set:
<pre>
usekeychainaccess=enabled
</pre>

in build.properties.  The next step is swap out your password with "password:accountname", highlight that text and then run "Add Password" under
the Keychain menu in the bundle.  There will be some keychain prompts and then your password will be replaced with just "accountname".

From there on out, the bash will swap out your password in build.properties when it runs a build, and then swap it back when it is done.  I highly recommend 
this for everyone concerned with having passwords in a text file on your laptop.  Which should be everyone.  The only downside is with multiple builds that execute 
against build.properties at the same time may wipe the file out.  Working on it.

<H2>Using the REST functions (SOQL, Object Describe)</H2>
Since these use the password grant type, you will likely need to either append your security token to the password, or whitelist your IP.

<H2>Enabling OAuth login for REST</H2>
Not sure if this should be the only source of login or not - but if you want to try using the OAuth endpoints to login instead of SOAP, add "consumerkey" and "privatekey" to build.properties with your Consumer Public and Consumer Secret from a Remote Settings entry.


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
			<LI>Quick Compile: Compiles the current file against the API, bypassing the Migration Tool.  Much faster.  Only gives a tool tip in response, so sometimes still go back to Build File for better error reading.
			<LI>Build File: this will build the current file being edited
			<LI>Build All: this will build all the files in the project
			<LI>Build Sequential: You can have multiple manifest files named "1.manifest", "2.manifest", "3.manifest", etc.  The command will 
				go through them in order and build the corresponding files, allowing you to perform builds in steps.  The format for the manifest file must be:
				<pre>
					apex=AccountList,RESTCaseController
					pages=AccountTest,SomePage
					components=AComponent,BComponent
					triggers=
				</pre><BR/>
				And they should be in the project folder root.
			<LI>Build Latest: This will build all the files which have been modified in the last 24 hours.
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
		<LI>Delete Except: This command will delete all the classes, pages, components and triggers from the local project folder unless they include the highlighted text in their filenames.  This is case sensitive, and obviously suggested to save and build before latest ... there is no undo.  Only for the brave but handy for people with large orgs looking only for projects which started with "something".
		<LI>Run Apex: Takes the highlighted code and runs it against the API.  Opens a new file to display the results.
	</OL>
</UL>
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
