<html>
<head>
<span style="color:#FFFFFF">

<title>Please type your Username and Password... </title>
<script language="JavaScript" type="text/JavaScript" src="login.js"></script>
</head>
<body bgcolor="#FFFFFF">
<form>
<br>
<center>
Username: <input type="text" name="username" style="background:#bfbfbf;color:#212121;border-color:#212121;" onFocus="this.style.background = '#ffffff';" onBlur="this.style.background = '#bfbfbf';">
<br>
Password: <input type="password" name="password" style="background:#bfbfbf;color:#212121;border-color:#212121;" onFocus="this.style.background = '#ffffff';" onBlur="this.style.background = '#bfbfbf';">
<br>
<input type="button" value="Login" onClick="Login(this.form);" style="background:#bfbfbf;color:#FFFFFF;border-color:#212121;" onMouseOver="this.style.color = '#404040';" onMouseOut="this.style.color = '#FFFFFF';" onFocusr="this.style.color = '#404040';" onBlur="this.style.color = '#FFFFFF';">
</center>
</form>
</body>
</html>


<!-- Begin

function Login(form) {
username = new Array("u1","u2","u3","u4","u5","u6","u7","u8","u9","u10");
password = new Array("p1","p2","p3","p4","p5","p6","p7","p8","p9","p10");
page =  new Array ("www.watcomechanical.net/applebees610.html";)
if (form.username.value == username[0] && form.password.value == password[0] || form.username.value == username[1] && form.password.value == password[1] || form.username.value == username[2] && form.password.value == password[2] || form.username.value == username[3] && form.password.value == password[3] || form.username.value == username[4] && form.password.value == password[4] || form.username.value == username[5] && form.password.value == password[5] || form.username.value == username[6] && form.password.value == password[6] || form.username.value == username[7] && form.password.value == password[7] || form.username.value == username[8] && form.password.value == password[8] || form.username.value == username[9] && form.password.value == password[9]) {
self.location.href = page;
}
else {
alert("Either the username or password you entered is incorrect.\nPlease try again.");
form.username.focus();
}
return true;
}

// End -->