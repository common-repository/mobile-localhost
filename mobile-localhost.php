<?php
/**
 * @package MobileLocalhost
 */
/**
* Plugin Name: Mobile Localhost
* Plugin URI: https://w3blogging.com/mobile-localhost-plugin
* Description: enable to connect your laptop localhost wordpess website with mobile device. helps to developed, test directly in mobile device or other device using wifi.
* Version: 1.0.9
* Text Domain: mobile-localhost
* Domain Path: /languages
* Author: Gohilar
* Author URI: http://gohilar.com
* License: GPL v3 or later
* License URI: http://www.gnu.org/licenses/gpl-3.0.html

------------------------------------------------------------------
Mobile Localhost
Copyright@ 2020 gohilar

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

//option hendeler
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

//it will take care of sets and function work
if ( !class_exists('GohilarMolo')) 
{
	class GohilarMolo 
	{
		//plugin file name constructed
		protected $plugin;
		public $mainurl;
		public 	$wppath;
		public $ip_address;
		public $molo_ip_address;
		public $molo_ip;
		public $whitelist;
		public $wphost;
		public $admin_toolbar_status;
			

		function __construct() {
			//define plugin path
			$this->plugin = plugin_basename(__FILE__);
			
			//define website home_url as variable $mainurl 
			$this->mainurl = home_url();
			
			//split $mainurl into website path and called it $wppath
			$this->wppath = parse_url($this->mainurl, PHP_URL_PATH);
			
			//geting pc ip address and define as $ip_address 
			$this->ip_address = getHostByName(getHostName());
			
			//spit $mainurl and website host known as $wphost
			$this->wphost = parse_url($this->mainurl, PHP_URL_HOST);

			/*
			if above get ip address is ::1 then asign it to 127.0.0.1 for  better management
			otherwise set as what we get;
			after this condition we call final ip as $molo_ip_address
			*/
				if ($this->ip_address == "::1") {
						$this->molo_ip_address = "127.0.0.1";
					}
			   else {
						$this->molo_ip_address = $this->ip_address;
					}

			//asign molo ip address now called $molo_ip	
			 $this->molo_ip = $this->molo_ip_address;

			//defining 127.0.0.1 or ::1 as a $whitelist (use for define whether localhosted or not)
			 $this->whitelist = array(
										'127.0.0.1',
										'::1'
										);
										
			//For Admin Toolbar Title: define whether molo is 'on'	or 'off' base on condition
				//if website wphost is ip address of computer than we called molo is on otherwise off
			if ($this->wphost == $this->molo_ip_address) {
			$this->admin_toolbar_status = '<span style=" background: #02ca02; margin-right: 10px; border-radius: 10px; ">&nbsp;&nbsp;&nbsp;</span>Molo';
			}
			else {
			$this->admin_toolbar_status = '<span style=" background: #bebebe; margin-right: 10px; border-radius: 10px; ">&nbsp;&nbsp;&nbsp;</span>Molo';
			}	
	
		}
	
	
		//Registering Actions
		function register() {
			//registering plugin stylesheet
			add_action('admin_enqueue_scripts', array( $this, 'enqueue' ) );
			
			//registering Submenu under Tools Tab
			add_action ( 'admin_menu', array( $this, 'molo_admin_menu' ) );
			
			//registering settings link on plugin page
			add_filter("plugin_action_links_$this->plugin", array($this, 'settings_link'));
			
			//registering molo admin toolbar
			add_action( 'wp_before_admin_bar_render', 'gohilar_molo_admin_bar', 999 );
		}
	
	
		//Add setting Link to Installed Plugin->mobile localhost->near deactivation
		public function settings_link ($links) {
			$settings_link = '<a href="tools.php?page=gohilar-mobile-localhost.php">Settings</a>';
			array_unshift($links, $settings_link); 
			return $links;
		}

		//adding Submenu under Tools Tab
		public function molo_admin_menu() {
			add_submenu_page ( 'tools.php', 'Mobile Localhost', 'Mobile Localhost', 'manage_options', 'gohilar-mobile-localhost', 'gohilar_mobile_localhost_page' );
		}
	
		//calling molo plugin stylesheet
		function enqueue() {
			wp_enqueue_style('gohilar-molo-style', plugins_url('style.css',__FILE__ ) );
		}
	
	//molo specific Action Function
	
		//function to shows Massage when molo is already off
		public function already_off() {
			echo '<div class="notice notice-error"> 
			<p>Molo Is already OFF</p>
			</div>';
		}
	
		//function to shows Massage when molo is already ON
		public function already_on() {
			echo '<div class="notice notice-error"> 
			<p>Molo Is already ON</p>
			</div>';
		}		
	
		//function to turn off molo
			//when this function called plugin will change url path to "localhost/$wppath" and redirected to localhost plugin page
		function turn_off() {
			update_option( 'siteurl', 'http://localhost' . $this->wppath .'/' );
			update_option( 'home', 'http://localhost' . $this->wppath .'/' );
			wp_redirect('http://localhost' . $this->wppath .'/wp-admin/tools.php?page=gohilar-mobile-localhost');
		}
	
		//function to turn on molo
				//when this function called plugin will change url path to "localhost/$wppath" and redirected to localhost plugin page
		function turn_on() {
			update_option( 'siteurl', 'http://'. $this->molo_ip . $this->wppath .'/' );
			update_option( 'home', 'http://'. $this->molo_ip .$this->wppath .'/' );
			wp_redirect('http://'. $this->molo_ip . $this->wppath .'/wp-admin/tools.php?page=gohilar-mobile-localhost');
		}
	
		//function to show massage error notice when try to activate on live website
		function error_notice() {
			echo'<div class="notice notice-error"> 
			<p>your website is hosted on the internet, Function of mobile localhost Not available for Live website</br>Molo is only for localhosted website</p>
			</div>';
		}
	
		//function to run on activation
		function activate() {}

		//function to run on deactivation
		function deactivate() {}

		//function to run on deactivation
		function unistall() {}	
	
	}
	
//class end
	$gohilarMolo = new GohilarMolo();
	$gohilarMolo->register();

	//activation plugin
	register_activation_hook(__FILE__, array( $gohilarMolo, 'activate') );

	//deactivation plugin
	register_activation_hook(__FILE__, array( $gohilarMolo, 'deactivate') );

}


/**
 * Molo on tools options page
 */
 
function gohilar_mobile_localhost_page() {
	global $gohilarMolo;
?>


<!------------------- Mobile Localhost page ----------------->	


<div class="wrap molo-wrap">
	<h1>Molo: Mobile localhost 
		<span id="moloby">
			Plugin by <a href="https://w3blogging.com/mobile-localhost-plugin" target="_blank">W3blogging</a>
		</span>
	</h1>

<?php 

/*-------------------- Warning & Error ------------------ */

	//warning if "host is localhost or host is pc ip address" ---> to take backup
		if ($gohilarMolo->wphost == "localhost" || $gohilarMolo->wphost == $gohilarMolo->molo_ip_address ) {
			echo'<div class="notice notice-warning"> 
		<p>Before Activate Molo, Please Take a backup of the website</p>
		</div>';
		}
	//error else (on live hosted website) ---> molo is not allow
		else{
		$gohilarMolo->error_notice();	
		}
?>		
	
	<!-------------------- IP Address Details ------------------>
	<div>
		 <?php		
			echo '(localhost ip is '. $gohilarMolo->ip_address .' & ' ;
			echo '<span>your IPv4 Address is <b>';
			echo $gohilarMolo->molo_ip;
		?>
		</b>)
		</span>
	</div>
	
	<!------- website IP Address ----->	
	
	<h3 id="moloheader">Your Website URL Address is 
		<a href="<?php echo home_url(); ?>">
			<?php echo home_url(); ?>
		</a>
	</h3>
	<hr>

<?php 
/*
//-------------------- Testing Details for developer -----------------
echo '<h2>TEST ME </h2>';
//only for developer to test variable work or not
echo '</br>ip_address : ' .$gohilarMolo->ip_address;
echo '</br>molo_ip: ' . $gohilarMolo->molo_ip;
echo '</br>home_url: ' . home_url();
//echo '</br>Server remote host : ' .$_SERVER['REMOTE_HOST']; 
echo '</br>server remote addr : ' .$_SERVER['REMOTE_ADDR'];
echo '</br>parse url wphost : ' .$gohilarMolo->wphost;
echo '</br>parse url wppath : ' .$gohilarMolo->wppath;

*/
?>	
	


<!--radio button navigation tab ---->
<div class="molo-content" >

<input type="radio" name="tabs" id="molotab1" checked />
<label for="molotab1">Action</label>

<input type="radio" name="tabs" id="molotab2" />
<label for="molotab2">Please Note</label>

<!----------- Tab 1: Molo Action centre  --------------------->

<div class="molotab molocontent1">

<!----------- step-1 Connect with Laptop with wifi  --------------------->

<div class="molo-step1">
	<h2>Step 1: Connect Laptop with wifi</h2>
		Please read Note Terms and Instruction
	<p style="font-size:18px;">Connect your Laptop with mobile Hotspot or connect mobile & laptop with the same wifi.</p>

</div>
<br>

<!------------------------- Step 2: Turning On mobile localhost function: --------------------------->

<div class="molo-step2">
	<h2>Step 2: Turn On Molo (Mobile Localhost)</h2>
	<p>Activate To connect localhost with mobile</p>
	<form method="post" class="molo-form">

		<?php

		//form radio button and info show when condtion setisfied
		if ($gohilarMolo->wphost == $gohilarMolo->molo_ip_address) {
			echo '<div class="molo-status"><p>Molo is <span id="molo-status-on">ON</span></p> <h4>Whould you would like to</h4>';
			echo '<div class="molo-turn-on"> <input type="radio" name="action" value="on_molo" checked="checked" /><label for="On">on Mobile Localhost</label></div>';
			echo '<div class="molo-turn-off"> <input type="radio" name="action" value="off_molo" required /><label for="Off">Off Mobile localhost</label><span id="molo-off-msg"> (Mobile Local host already active.</br> want to deactivate? Click on Turn Off)</span></div></div>';
		}

		elseif  ($gohilarMolo->wphost == "localhost" )	{
			echo '<div class="molo-status"><p>Molo is <span id="molo-status-off">Off</span></p> <h4>Would you like to</h4>';
			echo '<div class="molo-turn-on"> <input type="radio" name="action" value="on_molo" required /><label for="On">on Mobile Localhost</label><span id="molo-on-msg"> (Turn On to  activate Mobile Localhost)</span></div>';
			echo '<div class="molo-turn-off"> <input type="radio" name="action" value="off_molo" checked="checked" /><label for="Off">Off Mobile localhost</label></div></div>';
			
		}


		elseif (in_array($_SERVER['REMOTE_ADDR'], $gohilarMolo->whitelist) || $gohilarMolo->molo_ip_address == $_SERVER['REMOTE_ADDR'] || (in_array($gohilarMolo->molo_ip, $gohilarMolo->whitelist) )){
			$gohilarMolo->turn_on();
		}

		else {
					echo '<p class="molo-not-allow"> We are Sorry!, but MOLO Not Allowed on the live hosted website';
			echo '</br>Molo made for the development of website on localhost</p>';
			
		}

		?>

	
<input class="molo-form-submit" type="submit" name="go" onclick="return confirm('Are you sure?');" />


			<!------------------------- Function when click on on or off molo --------------------------->

<?php
	//is user is admin
	if (!is_admin()) {
		return;
	} else {
		//conditional function for turning on or off
		if(isset($_POST['go']) && $_POST['action'] == "on_molo" && in_array($_SERVER['REMOTE_ADDR'], $gohilarMolo->whitelist) && $gohilarMolo->wphost == "localhost"  ){
	
				$gohilarMolo->turn_on();
		}
		
		else if(isset($_POST['go']) && $_POST['action'] == "on_molo" && $gohilarMolo->wphost == $gohilarMolo->molo_ip_address  ) {
			echo '<div id="molo-already-on">molo is already On</div>';
			$gohilarMolo->already_on();
		}

		elseif (isset($_POST['go']) && $_POST['action'] == "off_molo" && $gohilarMolo->wphost == $gohilarMolo->molo_ip_address && $gohilarMolo->wphost != "localhost" )  {

			$gohilarMolo->turn_off();
		}
		elseif (isset($_POST['go']) && $_POST['action'] == "off_molo" && in_array($_SERVER['REMOTE_ADDR'], $gohilarMolo->whitelist) && $gohilarMolo->wphost == "localhost"  ) {
			echo '<div id="molo-already-off" style="color:red;margin: 1em;font-size: 18px;">molo is already Off</div>';
			$gohilarMolo->already_off();
		}

		elseif (isset($_POST['go']) && !in_array($_SERVER['REMOTE_ADDR'], $gohilarMolo->whitelist) && $gohilarMolo->wphost != "localhost") {
			echo '<p id="molo-sorry-msg"> So Sweet, but we cant allow this plugin on live website';
			echo '</br>thats good for your website:)</p>';
		}
	}

?>

</form>
</div>
<!---------end step 2 end form -------------->
<br>
<!----------- step-3 information and instruction --------------------->
<div class="molo-step3">
	<h2>Step 3: open website on Mobile</h2>
	<p>Congratulation!, All Done now open website on mobile:</p>
	<div class="molobox3">
		<p>- Open your Mobile Web Browser (i.e Chrome, Firefox, etc)</p>
		<p>- On your Mobile Browser Address Bar Write
			<span>
				<a href="<?php echo 'http://'. $gohilarMolo->molo_ip .$gohilarMolo->wppath .'/'; ?>">
					<?php echo 'http://'. $gohilarMolo->molo_ip .$gohilarMolo->wppath .'/'; ?>
				</a>
			</span>
		</p>
	</div>	
	

		

</div>
</br>

	</div>

</br>
<!----------- Molo admon page please note tab--------------------->
<div class="molotab molocontent2">
	<h2>Please Read</h2>
	<ol>
		<li>Make turn ON Molo only when want to use, otherwise make it OFF. Also after use Molo, please turn Off.</li>
		<li  style="color:red;">Please Don't change the laptop wifi device while Molo is ON. As Molo uses ipv4 it not possible to switch to new IP directly.</li>
		<li style="color:red;">so, if you need to change wifi while Molo is On, first turn Off Molo then change wifi and again Turn On Molo.</li>

		<li>if your laptop change connected wifi device then your localhosted website will not run. but if such a thing happens or any problem occurs, Don't worry change website address directly through this simple address:</li>
		<ol style="list-style-type: lower-roman;">
			<li>Go to Your phpMyAdmin database maybe this <a href="http://localhost/phpmyadmin/">http://localhost/phpmyadmin/</a> --> website database name --> select 'wp_options' then click on edit of 'siteurl' and 'home' (from option_name field) one bye one. and change the value of IP (i.e option_value) with the following (copy following URL and paste) in both(siteurl and home)in a big textarea.</li>
						This if localhost wordpress website<span style="color:green;font-size:15px;font-weight:700px;">
				<a href="<?php echo 'http://localhost' .$gohilarMolo->wppath .'/'; ?>">
					<?php echo 'http://localhost' .$gohilarMolo->wppath .'/'; ?>
				</a>
			</span>
			</br>
									This if Internet Hosted website<span style="color:green;font-size:15px;font-weight:700px;">
				<a href="<?php echo  home_url(); ?>">
					<?php echo  home_url(); ?>
				</a>
			</span>
			<li>All done you return to normal localhost.</li>
		</ol>
		</ul>
		<p>You can contact us here for any help or instruction</p>		

		<li>This plugin made only for developing websites on localhost WordPress, not for the Live hosted website.</li>
		<li>Don't use the plugin in the live hosted website on the internet. Trying to connect your online hosted live website may break your website's main URL.</li>
		<li>please take a backup of the current localhost website before activation.</li>
		<li>if you want to host the localhost website on the internet please the First turn off Molo and then take backup.</li>
		<li>For any problem please follow steps given in above (4)</li>
	</ol>

	<h2>contact us</h2>
	<p>if You face any problem like, your website is not opening, not working properly or website address not work or other, and you enable to solve that problem. kindly feel free to contact us.</p>
	you can also directly contact us.
	<a href="https://W3blogging.com/mobile-localhost-plugin/Plugin-Support/">W3blogging.com/mobile-localhost-plugin/Plugin-Support/</a>
	
</div>

</div>

</div>
 
<?php
}
/* -------------- Molo Admin Page end --------------*/ 

/*--------------Admin Toolbar (admin status bar) molo status --------------?*/
	// Add Admin Toolbar Menus

function gohilar_molo_admin_bar() {
	global $wp_admin_bar;
	global $gohilarMolo;
	
	//function to add parent molo on admin toolbat
	$args = array(
		'id'     => 'gohilar-molo-bar',
		'title'  =>  $gohilarMolo->admin_toolbar_status,
		'href' => admin_url( 'tools.php?page=gohilar-mobile-localhost' ),
		        'meta' => array(
            'class' => 'gohilar-molo-bar', 
            'title' => 'Molo - Mobile Localhost'
            ),
	);
	$wp_admin_bar->add_menu( $args );
	
	//submanu for turn on molo 
		
		//condition to run function of turning on
		  if (isset($_POST['gohilar-molo-turning-on']) && in_array($_SERVER['REMOTE_ADDR'], $gohilarMolo->whitelist) && $gohilarMolo->wphost == "localhost") {
			$gohilarMolo->turn_on();
		  }
		  elseif (isset($_POST['gohilar-molo-turning-on']) && $gohilarMolo->wphost == $gohilarMolo->molo_ip_address) {
			echo $gohilarMolo->already_on();
		
		  }
		  elseif (isset($_POST['gohilar-molo-turning-on'])) {
			  
			$gohilarMolo->error_notice();
		  }
	
		//adding submany to admin toolbar molo
		$args = array(
			'id'     => 'gohilar-molo-bar-on',
			'parent' => 'gohilar-molo-bar',
			'title'  => '<form action="" method="post"><button style="	background: 0; color: #f0f5fab3;border: none;overflow: hidden;font-size: 14px; line-height: 17px; cursor: pointer;" ><input type="hidden" value="true" name="gohilar-molo-turning-on" >Turn ON Molo</button></form>',
					'meta' => array(
				'class' => 'gohilar-molo-bar-on', 
				'title' => 'Turn On Molo'
				)
		);
		$wp_admin_bar->add_menu( $args );
	
		
		//condition to run function of turning off
		  if (isset($_POST['gohilar-molo-turning-off']) && $gohilarMolo->wphost == $gohilarMolo->molo_ip_address && $gohilarMolo->wphost != "localhost" ) {
			$gohilarMolo->turn_off();
		  }
		  elseif (isset($_POST['gohilar-molo-turning-off']) && in_array($_SERVER['REMOTE_ADDR'], $gohilarMolo->whitelist) && $gohilarMolo->wphost == "localhost" ) {
			$gohilarMolo->already_off();
		  }
		  elseif (isset($_POST['gohilar-molo-turning-off'])) {
			$gohilarMolo->error_notice();	
		  }
	
		//adding submanu to admin toolbar
		$args = array(
		'id'     => 'gohilar-molo-bar-off',
		'parent' => 'gohilar-molo-bar',
		'title'  => '<form action="" method="post"><button style="	background: 0; color: #f0f5fab3;border: none;overflow: hidden;font-size: 14px; line-height: 17px; cursor: pointer;"><input type="hidden" value="true" name="gohilar-molo-turning-off">Turn OFF Molo</button></form>',
				  'meta' => array(			  
				'class' => 'gohilar-molo-bar-off', 
				'title' => 'Turn Off Molo'
					)
		);
		$wp_admin_bar->add_menu( $args );
}

?>