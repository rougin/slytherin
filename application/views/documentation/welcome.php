<h1>Greetings Human!</h1>
<p>
	Hi! Welcome to the Slytherin Documentation. In this guide we will show you how to setup, install and configure Slytherin. Let's get started!
</p>
<div id="about-slytherin">
	<h1>What is Slytherin?</h1>
	<p>
		Slytherin is a simple and easy to understand PHP skeleton framework that uses a Model-View-Controller (MVC) software pattern. Unlike other other frameworks
		that provides big libraries that is unnecessary in building your wep application or site, Slytherin uses Composer to add, update or even remove external
		libraries with ease. With these, you have the right to select the libraries that you want to use, minimizing the size of your project and it also provides
		flexibility to the framework.
	</p>
</div>
<h1>Installation</h1>
<p></p>
<h1>The MVC (Model-View-Controller)</h1>
<p>
	The Model-View-Controller is a software pattern for implementing user interfaces. It divides a given software application into three interconnected parts,
	so as to seperate internal representations of information from the ways that information is presented to or accepted from the user. (Reenskaug and Coplien, 2009)
</p>
<ul>
	<li>Controller</li>
	<p>
		It can send commands to the model to update the model's state. It can also send commands to 
		its associated view to change the view's presentation of the model.
	</p>
	<li>Model</li>
	<p>
		It notifies its associated view/views and controllers when there has been a change in its state. This notification allows views to update their presentation, 
		and the controllers to change the available set of commands. In some cases an MVC implementation might instead be "passive", so that other components must 
		poll the model for updates rather than being notified.
	</p>
	<li>View</li>
	<p>
		It is told by the controller that all the information it needs for generating an output representation to the user. It can also provide generic mechanisms to 
		inform the controller of user input.
	</p>
</ul>