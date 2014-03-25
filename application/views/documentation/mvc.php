<h3 class="page-header">Model-View-Controller</h3>
<div class="row-placeholder">
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
</div>