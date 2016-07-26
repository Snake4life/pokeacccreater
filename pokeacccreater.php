<!DOCTYPE html>
<html>
<title>Pokemon Go Ptc Account Creator</title>
<body>
<center>
<form method="POST">
    Username:<br>
    <input type="text" name="username" value="<?php echo $_POST['username']?>">
    <br>
    Password:<br>
    <input type="text" name="password" value="<?php echo $_POST['password']?>">
    <br><br>
    <input type="submit" value="Create Account">
</form>

<?php
/**
 * User: Super
 * Date: 7/25/2016
 * Time: 4:25 PM
 */

if(isset($_POST['username']) && isset($_POST['password'])) {
    $name = $_POST['username'];
    $pass = $_POST['password'];
    $result = curl('https://club.pokemon.com/us/pokemon-trainer-club/sign-up/', null, null);
    preg_match('/{xpid:"(.*)="}/', $result, $mac);
    $xpid = $mac[1];
    preg_match('/window.token = "(.*)";/', $result, $mac);
    $token = $mac[1];
    if (!$mac) {
        die('Sorry Servers Down :( ');
    }
    $data = 'csrfmiddlewaretoken=' . $token . '&dob=1980-07-26&country=US&country=US';
    curl('https://club.pokemon.com/us/pokemon-trainer-club/sign-up/', $data, null);
    $data = '{"name":"' . $name . '"}';
    $headers = array(
        'X-NewRelic-ID:' . $xpid,
        'X-CSRFToken:' . $token,
        'Content-Type: application/json'
    );
    $checkName = curl('https://club.pokemon.com/api/signup/verify-username', $data, $headers);
    $result = json_decode($checkName, true);
    if ($result['valid'] == false || $result['inuse'] == true) {
        echo "<br>Bad Username <br>";
        foreach ($result['suggestions'] as $key) {
            echo "Try :" . $key . "<br>";
        }
        die();
    }
    $email = getEmail();
    $data = 'csrfmiddlewaretoken=' . $token . '&username=' . $name . '&password=' . $pass . '&confirm_password=' . $pass . '&email=' . $email . '&confirm_email=' . $email . '&public_profile_opt_in=False&screen_name=&terms=on';
    $result = curl('https://club.pokemon.com/us/pokemon-trainer-club/parents/sign-up', $data, null);
    preg_match('/<html class="(.*)">/', $result, $ma);
    if (!$ma) {
        Echo "<br><b>Account Sucessfully  Created</b> <br> Username = <b>" . $name . "</b> <br> Password = <b>" . $pass . "</b><br> Fake Email = <b>" . $email . "</b><br> Login Here : <b>https://sso.pokemon.com/sso/login</b> <br>";

    } else {
        echo "<br>The length of Password must be between 6 and 15 characters<br>";
    }
}
function curl($url,$data=null,$headers=null){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if($data != null){
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    }
    if($headers != null){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($ch,CURLOPT_REFERER,'https://club.pokemon.com/us/pokemon-trainer-club/parents/sign-up');
    curl_setopt($ch,CURLOPT_COOKIEFILE,'cookie.txt');
    curl_setopt($ch,CURLOPT_COOKIEJAR,'cookie.txt');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //curl_setopt($ch, CURLOPT_PROXY, '192.168.8.102:8888');
    $answer = curl_exec($ch);
    return $answer;

}
function getEmail($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString.'@dayrep.com';
}
?>
</center>
</body>
</html>
