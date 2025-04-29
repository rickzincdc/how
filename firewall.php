	

	<form method="POST" action="#" name="firewall" id="firewall">			
		<div class="campo">
			<label for="cid_estado">Cidade/Estado:</label>
			<input id="cid_estado" name="cid_estado" maxlength="25" type="text" />
		</div>
		<div class="campo">
			<label for="red_posto">Rede do Posto: </label>
			<input id="red_posto" name="red_posto" type="text" />
		</div>
		<div class="campo">
			<label for="tunnel">Tunnel: </label>
			<input id="tunnel" name="tunnel" type="text" />
		</div>
		<div class="campo">
			<label for="psk">PSK: </label>
			<input id="psk" name="psk" type="text" />
		</div>
		<div class="campo">
			<label for="peer_vpn">Peer de VPN: </label>
			<input id="peer_vpn" name="peer_vpn" type="text" />
		</div>
		<input type="submit" name="firewall" id="firewall" value="Gerar Script">
	</form>

<?php
	$result = "";
	if(isset($_POST['firewall'])){
		$cid_estado = $_POST["cid_estado"];
		$red_posto = $_POST["red_posto"];
		$tunnel = $_POST["tunnel"];
		$psk = $_POST["psk"];
		$peer_vpn = $_POST["peer_vpn"];
		
		$result ="<h5> SCRIPT </h5>
				set vsys vsys3 address cred_" . $cid_estado ." ip-netmask " . $red_posto . "<br>
				set vsys vsys3 address-group grp_rd_crediamigo static cred_" . $cid_estado ."<br>
				set vsys vsys1 address cred_" . $cid_estado ." ip-netmask " . $red_posto . "<br>
				set vsys vsys1 address-group grp_rd_crediamigo static cred_" . $cid_estado ."<br>
				
				
				set network interface tunnel units tunnel." . $tunnel . " ipv6 enabled no <br>
				set network interface tunnel units tunnel." . $tunnel . " ipv6 interface-id EUI-64 <br>
				set network interface tunnel units tunnel." . $tunnel . " <br>
				set network virtual-router VR_VSYS3 interface tunnel." . $tunnel . " <br>
				set vsys vsys3 zone VPN_Zone_Crediamigo network layer3 tunnel." . $tunnel . " <br>

				set network ike gateway cred_" . $cid_estado ." protocol version ikev2 <br>
				set network ike gateway cred_" . $cid_estado ." protocol ikev2 ike-crypto-profile bnb_ikev2_phase_1 <br>
				set network ike gateway cred_" . $cid_estado ." authentication pre-shared-key key " . $psk . " <br>
				set network ike gateway cred_" . $cid_estado ." protocol-common nat-traversal enable yes <br>
				set network ike gateway cred_" . $cid_estado ." protocol-common passive-mode no <br>
				set network ike gateway cred_" . $cid_estado ." local-address interface ethernet1/6.590 <br>
				set network ike gateway cred_" . $cid_estado ." local-address ip 198.17.121.247/27 <br>
				set network ike gateway cred_" . $cid_estado ." peer-address ip " . $peer_vpn . " <br>
				set network ike gateway cred_" . $cid_estado ." local-id type ipaddr id 198.17.121.247 <br>
				set network ike gateway cred_" . $cid_estado ." peer-id type ipaddr id " . $peer_vpn . " <br>

				set network tunnel ipsec cred_" . $cid_estado ." auto-key ipsec-crypto-profile bnb_ikev2_phase_2 <br>
				set network tunnel ipsec cred_" . $cid_estado ." auto-key ike-gateway cred_" . $cid_estado . "  <br>
				set network tunnel ipsec cred_" . $cid_estado ." tunnel-monitor enable no <br>
				set network tunnel ipsec cred_" . $cid_estado ." anti-replay yes <br>
				set network tunnel ipsec cred_" . $cid_estado ." copy-tos no <br>
				set network tunnel ipsec cred_" . $cid_estado ." tunnel-interface tunnel." . $tunnel . " <br>



				set network tunnel ipsec cred_" . $cid_estado ." auto-key proxy-id " . $cid_estado ."_01 local 128.15.0.0/16 remote " . $red_posto . " protocol any  <br>
				set network tunnel ipsec cred_" . $cid_estado ." auto-key proxy-id " . $cid_estado ."_02 local 200.164.107.0/24 remote " . $red_posto . " protocol any  <br>
				set network tunnel ipsec cred_" . $cid_estado ." auto-key proxy-id " . $cid_estado ."_03 local 10.0.0.0/8 remote " . $red_posto . " protocol any  <br>
				set network tunnel ipsec cred_" . $cid_estado ." auto-key proxy-id " . $cid_estado ."_04 local 128.2.1.6/32 remote " . $red_posto . " protocol any  <br>
				set network tunnel ipsec cred_" . $cid_estado ." auto-key proxy-id " . $cid_estado ."_05 local 130.0.0.0/8 remote " . $red_posto . " protocol any  <br>
				set network tunnel ipsec cred_" . $cid_estado ." auto-key proxy-id " . $cid_estado ."_06 local 192.168.74.0/26 remote " . $red_posto . " protocol any  <br>



				set network virtual-router VR_VSYS3 routing-table ip static-route cred_" . $cid_estado ." interface tunnel." . $tunnel . "  <br>
				set network virtual-router VR_VSYS3 routing-table ip static-route cred_" . $cid_estado ." metric 5  <br>
				set network virtual-router VR_VSYS3 routing-table ip static-route cred_" . $cid_estado ." destination " . $red_posto . "<br>

				set network ike gateway cred_" . $cid_estado ." protocol ikev2 dpd interval 5 <br>
				set network ike gateway cred_" . $cid_estado ." protocol ikev2 dpd enable yes <br>";
	
				echo $result;
	}

?>