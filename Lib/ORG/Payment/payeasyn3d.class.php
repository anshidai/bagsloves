<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-12-21
 */
class payeasyn3d {
	var $fields = array();           // array holds the fields to submit to paypal
	var $form='';					//表单
	var $submit_url;				//提交地址
	function __construct() {
		$this->submit_url="http://pay.beijing.com.cn/prs/e_user_payment.checkit"; 
	}
	function add_field($field, $value) {
		// adds a key=>value pair to the fields array, which is what will be
		// sent to paypal as POST variables.  If the value is already in the
		// array, it will be overwritten.
		$this->fields ["$field"] = $value;
	}
	function other() {
		$this->form.=<<<js
		<script language="JavaScript" type="text/javascript">
		
		function state_show(_this){
			if(_this.value==840){
				document.getElementById("state2").style.display="none";
				document.getElementById("state").style.display="";
			}
			if(_this.value==124){
				document.getElementById("state").style.display="none";
				document.getElementById("state2").style.display="";
			} 
			document.pay_form.v_shipcountry.value=_this.value;
		}
</script>
js;
		$this->form.=<<<country
		<table align="center">
		<tr><td colspan="2"><strong>Credit card 3D-Secure Payment</strong></td></tr>
		<tr id="country"><td>Please select Country:</td>
		<td>
		<select name="country" onchange="state_show(this);"> 
<option value="">--please select country--</option>
<option value="004">Islamic State of Afghanistan </option>
<option value="008">Republic of Albania </option>
<option value="010">Antarctica </option>
<option value="012">Democratic People's Republic of Algeria </option>
<option value="016">American Samoa </option>
<option value="020">Principality of Andorra </option>
<option value="024">Republic of Angola </option>
<option value="028">Antigua and Barbuda </option>
<option value="031">Republic of Azerbaijan </option>
<option value="032">Republic of Argentina </option>
<option value="036">Commonwealth of Australia </option>
<option value="040">Republic of Austria </option>
<option value="044">Commonwealth of the Bahamas </option>
<option value="048">State of Bahrain </option>
<option value="050">People's Republic of Bangladesh </option>
<option value="051">Republic of Armenia </option>
<option value="052">Barbados </option>
<option value="056">Kingdom of Belgium </option>
<option value="060">Bermuda Islands </option>
<option value="064">Kingdom of Bhutan </option>
<option value="068">Republic of Bolivia </option>
<option value="070">Republic of Bosnia and Herzegovina </option>
<option value="072">Republic of Botswana </option>
<option value="074">Bouvet Island </option>
<option value="076">Federative Republic of Brazil </option>
<option value="084">Belize </option>
<option value="086">British Indian Ocean Territory </option>
<option value="090">Solomon Islands </option>
<option value="092">British Virgin Islands </option>
<option value="092">Virgin Islands (British)</option>
<option value="096">Brunei Darussalam </option>
<option value="100">Republic ov Bulgaria </option>
<option value="104">Union of Myanmar </option>
<option value="108">Republic of Burundi </option>
<option value="112">Republic of Belarus </option>
<option value="116">Kingdom of Cambodia </option>
<option value="120">Republic of Cameroon </option>
<option value="124">Canada </option>
<option value="132">Republic of Cape Verde </option>
<option value="136">Cayman Islands </option>
<option value="140">Central African Republic </option>
<option value="144">Democratic Socialist Republic of Srilanka </option>
<option value="148">Republic of Chad </option>
<option value="152">Republic of Chile </option>
<option value="156">People's Republic of China</option>
<option value="158">Taiwan, Province of China</option>
<option value="162">Christmas Island </option>
<option value="166">Cocos(Keeling) Islands </option>
<option value="170">Republic of Colombia </option>
<option value="174">Federal Islamic Republic of the Co-moros </option>
<option value="175">Mayotte </option>
<option value="178">Republic of Congo </option>
<option value="180">Republic of Zaire </option>
<option value="184">Cook Islands </option>
<option value="188">Republic of Costa Rica </option>
<option value="191">Republic of Croatia </option>
<option value="192">Republic of Cuba </option>
<option value="196">Republic of Cyprus </option>
<option value="203">Czech Republic </option>
<option value="204">Republic of Benin </option>
<option value="208">Kingdom of Denmark </option>
<option value="212">Commonwealth of Dominica </option>
<option value="214">Dominican Republic </option>
<option value="218">Republic of Ecuador </option>
<option value="222">Republic of El Salvador </option>
<option value="226">Republic of Equatorial Guinea </option>
<option value="231">Ethiopia </option>
<option value="232">State of Eritrea </option>
<option value="233">Republic of Estonia </option>
<option value="234">Faroe Islands </option>
<option value="238">Malvinas islands (Falkland Islands) </option>
<option value="239">South Georgia and South Sandwich Islands </option>
<option value="242">Republic of Fiji </option>
<option value="246">Republic of Finland </option>
<option value="248">Aaland Islands</option>
<option value="250">French Republic </option>
<option value="254">French Guiana </option>
<option value="258">French Polynesia </option>
<option value="260">French Southern Territories </option>
<option value="262">Republic of Djibouti </option>
<option value="266">Gabonese Republic </option>
<option value="268">Republic of Georgia </option>
<option value="270">Republic of Gambia </option>
<option value="276">Federal Republic of Germany </option>
<option value="288">Republic of Ghana </option>
<option value="292">Gibraltar </option>
<option value="296">Republic of Kiribati </option>
<option value="300">Hellenic Republic </option>
<option value="304">Greenland </option>
<option value="308">Grenada </option>
<option value="312">Guadeloupe </option>
<option value="316">Guam </option>
<option value="320">Republic of Guatemala </option>
<option value="324">Republic of Guinea </option>
<option value="328">Cooperative Republic of Guyana </option>
<option value="332">Republic of Haiti </option>
<option value="334">Heard islands and Mc Donald Islands </option>
<option value="336">Vatican City State </option>
<option value="340">Republic of honduras </option>
<option value="344">Hong Kong </option>
<option value="348">Republic of Hungary </option>
<option value="352">Republic of Iceland </option>
<option value="356">Republic of India </option>
<option value="360">Republic of Indonesia </option>
<option value="364">Islamic Rupublic of Iran </option>
<option value="368">Republic of Iraq </option>
<option value="372">Ireland </option>
<option value="374">State of Palestine </option>
<option value="376">State of Israel </option>
<option value="380">Republic of Italy </option>
<option value="384">Republic of Cote d'Ivire </option>
<option value="388">Jamaica </option>
<option value="392">Japan </option>
<option value="398">Republic of Kazakhstan </option>
<option value="400">Hashemite Kingdom of Jordan </option>
<option value="404">Republic of Kenya </option>
<option value="408">Democratic People's Republic of Ko-rea </option>
<option value="410">Republic of Korea </option>
<option value="414">State of Kuwait </option>
<option value="417">Kyrgyz Republic </option>
<option value="418">Lao People's Democratic Republic </option>
<option value="422">Republic of Lebanon </option>
<option value="426">Kingdom of Lesoto </option>
<option value="428">Republic of Latvia </option>
<option value="430">Republic of Liberia </option>
<option value="434">Great Socialist People's Libyan Arab jamahiriya </option>
<option value="438">Principality of Liechtenstein </option>
<option value="440">Republic of Lithuania </option>
<option value="442">Grand Duchy of Luxembourg </option>
<option value="446">Macau </option>
<option value="450">Republic of Madagascar </option>
<option value="454">Republic of Malawi </option>
<option value="458">Malaysia </option>
<option value="462">Republic of maldives </option>
<option value="466">Republic of Mali </option>
<option value="470">Republic of Malta </option>
<option value="474">Martinique </option>
<option value="478">Republic of Mauritius </option>
<option value="480">Republic of Mauritius </option>
<option value="484">United States of Mexico </option>
<option value="492">Principality of Monaco </option>
<option value="496">Mongolia </option>
<option value="498">Republic of Moldova </option>
<option value="500">Montserrat </option>
<option value="504">Kingdom of Morocco </option>
<option value="508">Republic of Mozambique </option>
<option value="512">Sultanate of Oman </option>
<option value="516">Republic of Namibia </option>
<option value="520">Republic of Nauru </option>
<option value="524">Kingdom of Nepal </option>
<option value="528">Kingdom of the Netherlands </option>
<option value="530">Netherlands Antilles </option>
<option value="533">Aruba </option>
<option value="540">New Caledonia </option>
<option value="548">Republic of Vanuatu </option>
<option value="554">New Zealand </option>
<option value="558">Republic of Nicaragua </option>
<option value="562">Republic of Niger </option>
<option value="566">Federal Republic of Nigeria </option>
<option value="570">Niue </option>
<option value="574">Norfolk Island </option>
<option value="578">Kingdom of Norway </option>
<option value="580">Commonwealth of the Northern Marianas </option>
<option value="583">Federated States of Micronesia </option>
<option value="584">Republic of the marshall Islands </option>
<option value="585">Republic of Palau </option>
<option value="586">Islamic Republic of Pakistan </option>
<option value="591">Republic of Panama </option>
<option value="598">Independent State of Papua New Guinea </option>
<option value="600">Republic of Paraguay </option>
<option value="604">Republic of Peru </option>
<option value="608">Republic of the Philippines </option>
<option value="612">Pitcairn Islands Group </option>
<option value="616">Republic of Poland </option>
<option value="620">Pirtuguese Republic </option>
<option value="624">Republic of Guine-bissau </option>
<option value="626">East Timor </option>
<option value="630">Commonwealth of Puerto Rico </option>
<option value="634">State of Qatar </option>
<option value="638">Reunion </option>
<option value="642">Romania </option>
<option value="643">Russian Federation </option>
<option value="646">Republic of Rwanda </option>
<option value="654">Saint Helena </option>
<option value="659">Federation of Saint Kitts and nevis </option>
<option value="660">Anguilla </option>
<option value="662">Saint Lucia </option>
<option value="666">Saint Pierre and Miquelon </option>
<option value="670">Saint Vincent and the Grenadines </option>
<option value="674">Republic of San Marino </option>
<option value="678">Democratic Republic of Sao Tome and Principe </option>
<option value="682">Kingdom of Saudi Arabia </option>
<option value="686">Republic of Senegal </option>
<option value="690">Republic of Seychelles </option>
<option value="694">Republic of Sierra Leone </option>
<option value="702">Republic of Singapore </option>
<option value="703">Slovak Republic </option>
<option value="704">Socialist Republic of Viet Nam </option>
<option value="705">Republic of Slovenia </option>
<option value="706">Somali Republic </option>
<option value="710">Republic of South Africa </option>
<option value="716">Republic of Zimbabwe </option>
<option value="724">Spain </option>
<option value="732">Western Sahara </option>
<option value="736">Republic of the Sudan </option>
<option value="740">Republic of Suriname </option>
<option value="744">Svalbard and Jan mayen islands </option>
<option value="748">Kingdom of Swaziland </option>
<option value="752">Kingdom of Sweden </option>
<option value="756">Swiss Confederation </option>
<option value="760">Syrian Arab Republic </option>
<option value="762">Republic of Tajikistan </option>
<option value="764">Kingdom of Thailand </option>
<option value="768">Republic of Tago </option>
<option value="772">Tokelau </option>
<option value="776">Kingdom of Tonga </option>
<option value="780">Republic of Trinidad and Tobago </option>
<option value="784">United Arab Emirates </option>
<option value="788">Republic of Tunisia </option>
<option value="792">Republic of Turkey </option>
<option value="795">Turkmenistan </option>
<option value="796">Turks and Caicos Islands </option>
<option value="798">Tuvalu </option>
<option value="800">Republic of Uganda </option>
<option value="804">Ukraine </option>
<option value="807">Republic of Macedonia </option>
<option value="818">Arab Republic of Egypt </option>
<option value="826">United Kingdom of Great Britain and Northern ireland</option>
<option value="834">United Republic of Tanzania </option>
<option value="840">United States of America </option>
<option value="850">United States Virgin Islands </option>
<option value="854">Burkina Faso </option>
<option value="858">Oriental Republic of Uruguay </option>
<option value="860">Republic of Uzbekistan </option>
<option value="862">Republic of Venezuela </option>
<option value="876">Wallis and Futuna Islands </option>
<option value="882">Independent State of Western Samoa </option>
<option value="887">Republic of Yemen </option>
<option value="891">Federal Republic of Yugoslavia </option>
<option value="894">Republic of Zambia </option>
 
</select></td></tr>
	<tr id="state" style="display:none"><td>Please select state:</td><td>
	<select name="us_state" onchange="document.pay_form.v_shipstate.value=this.value;">
	<option value="">--please select state--</option>
<option value="AL">Alabama</option>
<option value="AK">Alaska</option>
<option value="AS">American Samoa</option>
<option value="AZ">Arizona</option>
<option value="AR">Arkansas</option>
<option value="CA">California</option>
<option value="CO">Colorado</option>
<option value="CT">Connecticut</option>
<option value="DE">Delaware</option>
<option value="DC">District Columbia</option>
<option value="FM">Federated States of Micronesia</option>
<option value="FL">Florida of</option>
<option value="GA">Georgia</option>
<option value="GU">Guam</option>
<option value="HI">Hawaii</option>
<option value="ID">Idaho</option>
<option value="IL">Illinois</option>
<option value="IN">Indiana</option>
<option value="IA">Iowa</option>
<option value="KS">Kansas</option>
<option value="KY">Kentucky</option>
<option value="LA">Louisiana</option>
<option value="ME">Maine</option>
<option value="MH">Marshall  Islands</option>
<option value="MD">Maryland</option>
<option value="MA">Massachusetts</option>
<option value="MI">Michigan</option>
<option value="MN">Minnesota</option>
<option value="MS">Mississippi</option>
<option value="MO">Missouri</option>
<option value="MT">Montana</option>
<option value="NE">Nebraska</option>
<option value="NV">Nevada</option>
<option value="NH">New Hampshire</option>
<option value="NJ">New Jersey</option>
<option value="NM">New Mexico</option>
<option value="NY">New York</option>
<option value="NC">North Carolina</option>
<option value="ND">North Dakota</option>
<option value="MP">Northern Mariana Islands</option>
<option value="OH">Ohio</option>
<option value="OK">Oklahoma</option>
<option value="OR">Oregon</option>
<option value="PW">Palau</option>
<option value="PA">Pennsylvania</option>
<option value="PR">Puerto Rico</option>
<option value="RI">Rhode Island</option>
<option value="SC">South  Carolina</option>
<option value="SD">South  Dakota</option>
<option value="TN">Tennessee</option>
<option value="TX">Texas</option>
<option value="UT">Utah</option>
<option value="VT">Vermont</option>
<option value="VI">Virgin  Islands</option>
<option value="VA">Virginia</option>
<option value="WA">Washington</option>
<option value="WV">West Virginia</option>
<option value="WI">Wisconsin</option>
<option value="WY">Wyoming</option>
</select>
	</td></tr>
	<tr id="state2" style="display:none"><td>Please select state:</td><td>
	<select name="ca_state" onchange="document.pay_form.v_shipstate.value=this.value;">
	<option value="">--please select state--</option>
<option value="AB">Alberta  
<option value="BC">British Columbia </option>
<option value="MB">Manitoba  </option>
<option value="NB">New Brunswick  </option>
<option value="NL">Newfoundland and Labrador  </option>
<option value="NT">Northwest Territories  </option>
<option value="NS">Nova Scotia  </option>
<option value="NU">Nunavut </option>
<option value="ON">Ontario  </option>
<option value="PE">Prince Edward Island  </option>
<option value="QC">Quebec </option>
<option value="SK">Saskatchewan  </option>
<option value="YT">Yukon  </option>
<option value="AB">Alberta</option>
　	　
</select>
</td> 
  </tr>
  </table>
country;
}
/**
	 * 新增
	 */
function create_form($list) {
	$pname=get_class($this);
	$this_script = "http://{$_SERVER['HTTP_HOST']}";

	$this->other();
	$this->add_field('v_mid',GetValue ( $pname . "_id" ));//商户编号
	$this->add_field('v_oid',date('Ymd').'-'.GetValue ( $pname . "_id" ).'-'.$list ['sn']);//订单编号
	$this->add_field('v_rcvname',$list['delivery_firstname']." ".$list['delivery_lastname']);//收货人姓名
	$this->add_field('v_rcvaddr',$list['delivery_address']);//收货人地址
	$this->add_field('v_rcvtel',$list['delivery_telephone']);//收货人电话
	$this->add_field('v_rcvpost',$list['delivery_zip']);//收货人邮编
	$this->add_field('v_amount',$list ['orders_total']);//订单总金额
	//$this->add_field('v_language','en');//订单语言
	$this->add_field('v_ymd',date('Ymd',$list['dateline']));//订单产生日期
	$this->add_field('v_orderstatus',1);//配货状态
	$this->add_field('v_ordername',$list['delivery_firstname']." ".$list['delivery_lastname']);//订货人姓名
	$this->add_field('v_moneytype',1);//币种,0为人民币,1为美元，2为欧元，3为英镑，4为日元，5为韩元，6为澳大利亚元
	$this->add_field('v_url',$this_script.U('Payment/payeasy_return'));//支付动作完成后返回到该url，支付结果以GET方式发送
	$this->add_field('v_md5info',bin2hex(mhash(MHASH_MD5,"1".date('Ymd',$list['dateline']).$list ['orders_total'].$list['delivery_firstname']." ".$list['delivery_lastname'].date('Ymd').'-'.GetValue ( $pname . "_id" ).'-'.$list ['sn'].GetValue ( $pname . "_id" ).$this_script.U('Payment/payeasy_return'),GetValue ( $pname . "_key" ))));//订单数字指纹
	$this->add_field('v_shipstreet',$list['delivery_address']);//送货街道地址
	$this->add_field('v_shipcity',$list['delivery_city']);//送货城市
	//$this->add_field('v_shipstate',$_POST['']);//送货省/州
	$this->add_field('v_shippost',$list['delivery_zip']);//送货邮编
	//$this->add_field('v_shipcountry','840');//送货国家
	$this->add_field('v_shipphone',$list['delivery_telephone']);//送货电话
	$this->add_field('v_shipemail',$list['delivery_email']);//送货Email
	$this->form.= "<form method=\"post\" name=\"pay_form\" ";
	$this->form.= "action=\"".$this->submit_url."\">\n";
	foreach ($this->fields as $name => $value) {
		$this->form.= "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
	}
	//非3d特殊
	$this->form.= "<input type=\"hidden\" name=\"v_shipstate\" value=\"\"/>\n";
	$this->form.= "<input type=\"hidden\" name=\"v_shipcountry\" value=\"\"/>\n";
	$this->form.= "</form>\n";
	if(GetValue($list['payment_module_code'].'_autosubmit')==1){
		$delay=GetValue($list['payment_module_code'].'_delay');
		$delay=$delay?$delay:5;
		$this->form.=$this->submit($delay);//是否自动提交,延迟5秒
	}
	return $this->form;
}
function submit($delay){
	if(!$delay){
		$this->form.= "<script>function jump(){ document.forms[\"pay_form\"].submit();} jump();</script>";
	}else{
		$delay*=1000;
		$this->form.= "<script>function jump(){ document.forms[\"pay_form\"].submit();} setTimeout('jump()',$delay);</script>";
	}
}
}
?>