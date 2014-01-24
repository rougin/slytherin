<html>
    <head>
        <title>{title}</title>
    </head>
    <body>
        <div>Please Fill out the form below</div>
        <div id="form">
            {form}
        </div>
    </body>
</html>
/* Inner template: bottle-form.tpl.php */
<form>
    Enter Bottle Color: <input type="text" name="color" />
    Enter Bottle Size: <input type="text" name="size" />
    <?php echo ';heh'; ?>
</form>