<?php
require_once 'class.functions.php';

class data extends db_connect
{

	const CONSTANT_EMAIL_RECEIVER = 'tomas.kellner@gmail.com';
	const defaultTitle = 'New Project';

	var $title = 'New Project';
	var $description = '';
	var $keywords = '';
	var $functions;

	// Konstruktor třídy products
	public function __construct()
	{
		$this->functions = new functions();
	}

	// Definice metatagu
	public function getMetaTags()
	{

		// Pokud se jedná o stránku
		if ($_GET['page']) {
			$data = $this->getContent($_GET['page']);
			$this->title = $data['nazev'] . " | " . self::defaultTitle;

			$url = $this->sqlInjection($_GET['page']);

			$sql = "SELECT title, description, keywords FROM " . $this->prefix . "menu WHERE url = '$url'";
			$result = $this->db()->query($sql);
			$row = mysqli_fetch_object($result);

			if (!empty(trim($row->title))) {
				$this->title = $row->title . " | " . self::defaultTitle;
			}
			if (!empty(trim($row->description))) {
				$this->description = $row->description;
			}
			if (!empty(trim($row->keywords))) {
				$this->keywords = $row->keywords;
			}

			if ($_GET['page'] == 'ucet') {
				$this->title = 'Můj účet | ' . self::defaultTitle;
			}
			if ($_GET['page'] == 'kosik') {
				$this->title = 'Košík | ' . self::defaultTitle;
			}

		}
		// Pokud jde o detail produktu
		if ($_GET['detail']) {
			$data = $this->products->getDetail($_GET['detail']);
			$row = mysqli_fetch_object($data);
			$this->title = $row->nazev . " | " . self::defaultTitle;
			$this->description = $this->functions->checkStringLength($row->popis, 250);

			$url = $this->sqlInjection($_GET['detail']);

			$sql = "SELECT title, description, keywords FROM " . $this->prefix . "produkty WHERE url = '$url'";
			$result = $this->db()->query($sql);
			$row = mysqli_fetch_object($result);

			if (!empty(trim($row->title))) {
				$this->title = $row->title . " | " . self::defaultTitle;
			}
			if (!empty(trim($row->description))) {
				$this->description = $row->description;
			}
			if (!empty(trim($row->keywords))) {
				$this->keywords = $row->keywords;
			}

			if ($_SESSION['lang'] == 'de') {
				$this->title = $row->nazev_de . " | " . self::defaultTitle;
				$this->description = $this->functions->checkStringLength($row->popis_de, 250);
			}
		}
		// Pokud jde o kategorii
		if ($_GET['kategorie']) {
			$get = explode('-', $_GET['kategorie']);
			$row = $this->getContentCategory($get[1]);
			$this->title = $row->nazev . " | " . self::defaultTitle;

			$id = $this->sqlInjection($get[1]);

			$sql = "SELECT title, description, keywords FROM " . $this->prefix . "kategorie WHERE id = '$id'";
			$result = $this->db()->query($sql);
			$row = mysqli_fetch_object($result);

			if (!empty(trim($row->title))) {
				$this->title = $row->title . " | " . self::defaultTitle;
			}
			if (!empty(trim($row->description))) {
				$this->description = $row->description;
			}
			if (!empty(trim($row->keywords))) {
				$this->keywords = $row->keywords;
			}

		}
	}

	public function getNextPrev(){

		$sql = "SELECT id,url FROM ".$this->prefix."menu WHERE pod = '1' AND aktivni ORDER BY poradi ASC";
		$result = $this->db()->query($sql);
		while($row = mysqli_fetch_object($result)){

			$data[] = $row->url;

		}

		return $data;

	}
	// Hlavní menu včetně submenu
	public function getMenuItems($header, $footer, $pod)
	{

		$pod = $this->sqlInjection($pod);
		$head = '';
		$foot = '';
		if ($header) {
			$head = " AND header='1'";
		}
		if ($footer) {
			$foot = " AND footer='1'";
		}

		$sql = "SELECT id, nazev, nazev_en, nazev_de, nazev_sk, url, ikona, foto, obsah, id_fotogalerie FROM " . $this->prefix . "menu WHERE aktivni='1' $head $foot AND !pod ORDER BY poradi ASC";

		if ($pod) {
			$sql = "SELECT id, nazev, nazev_en, nazev_de, nazev_sk, url, ikona, foto, obsah, id_fotogalerie FROM " . $this->prefix . "menu WHERE aktivni='1' AND pod='$pod' ORDER BY poradi ASC";
		}

		$result = $this->db()->query($sql);
		return $result;
	}

	// Výpíše bannery
	public function getBanners()
	{

		$sql = "SELECT nazev, nazev_de, nazev_en, nazev_sk, perex, perex_sk, perex_en, perex_de, url, foto, foto_en, foto_de, foto_sk FROM " . $this->prefix . "banner WHERE aktivni='1' ORDER BY poradi ASC";
		$result = $this->db()->query($sql);
		return $result;
	}

	// Obsah stránky (menu)
	public function getContent($url)
	{
		$url = $this->sqlInjection($url);

		$sql = "SELECT id, nazev, nazev_en, nazev_de, nazev_sk, obsah, obsah_en, obsah_de, obsah_sk, url, foto, ikona, h1,id_fotogalerie FROM " . $this->prefix . "menu WHERE url = '$url' AND aktivni='1'";
		$result = $this->db()->query($sql);
		$row = mysqli_fetch_object($result);

		if ($row->id_fotogalerie) {
			$sql1 = "SELECT url, nazev FROM " . $this->prefix . "fotogalerie WHERE id='$row->id_fotogalerie' AND aktivni";
			$result1 = $this->db()->query($sql1);
			$row1 = mysqli_fetch_object($result1);

			$url_fotogalerie = $row1->url;
			$nazev_fotogalerie = $row1->nazev;

			if (!$result->num_rows) {
				$row->id_fotogalerie = 0;
				$url_fotogalerie = '';
				$nazev_fotogalerie = '';
			}
		}

		$data = array(
			'id' => $row->id,
			'nazev' => $row->nazev,
			'nazev_en' => $row->nazev_en,
			'nazev_de' => $row->nazev_de,
			'nazev_sk' => $row->nazev_sk,
			'obsah' => $row->obsah,
			'obsah_en' => $row->obsah_en,
			'obsah_de' => $row->obsah_de,
			'obsah_sk' => $row->obsah_sk,
			'url' => $row->url,
			'foto' => $row->foto,
			'ikona' => $row->ikona,
			'h1' => $row->h1,
			'id_fotogalerie' => $row->id_fotogalerie,
			'url_fotogalerie' => $url_fotogalerie,
			'nazev_fotogalerie' => $nazev_fotogalerie,
		);

		return $data;
	}

	// Obsah stránky (menu)
	public function getContentById($id)
	{
		$id = $this->sqlInjection($id);

		$sql = "SELECT id, nazev, nazev_en, nazev_de, nazev_sk, obsah, obsah_en, obsah_de, obsah_sk, url, foto, ikona, h1,id_fotogalerie FROM " . $this->prefix . "menu WHERE id = '$id' AND aktivni='1'";
		$result = $this->db()->query($sql);
		$row = mysqli_fetch_object($result);

		if ($row->id_fotogalerie) {
			$sql1 = "SELECT url, nazev FROM " . $this->prefix . "fotogalerie WHERE id='$row->id_fotogalerie' AND aktivni";
			$result1 = $this->db()->query($sql1);
			$row1 = mysqli_fetch_object($result1);

			$url_fotogalerie = $row1->url;
			$nazev_fotogalerie = $row1->nazev;

			if (!$result->num_rows) {
				$row->id_fotogalerie = 0;
				$url_fotogalerie = '';
				$nazev_fotogalerie = '';
			}
		}

		$data = array(
			'id' => $row->id,
			'nazev' => $row->nazev,
			'nazev_en' => $row->nazev_en,
			'nazev_de' => $row->nazev_de,
			'nazev_sk' => $row->nazev_sk,
			'obsah' => $row->obsah,
			'obsah_en' => $row->obsah_en,
			'obsah_de' => $row->obsah_de,
			'obsah_sk' => $row->obsah_sk,
			'url' => $row->url,
			'foto' => $row->foto,
			'ikona' => $row->ikona,
			'h1' => $row->h1,
			'id_fotogalerie' => $row->id_fotogalerie,
			'url_fotogalerie' => $url_fotogalerie,
			'nazev_fotogalerie' => $nazev_fotogalerie,
		);

		return $data;
	}

	// Data kategorie
	public function getCategoryData($url)
	{
		$url = $this->sqlInjection($url);

		$sql = "SELECT id, nazev, nazev_en, nazev_de, nazev_sk, obsah, obsah_en, obsah_de, obsah_sk, url, foto, ikona, h1, pod FROM " . $this->prefix . "kategorie WHERE url = '$url'";
		$result = $this->db()->query($sql);
		$row = mysqli_fetch_object($result);

		$data = array(
			'id' => $row->id,
			'nazev' => $row->nazev,
			'nazev_en' => $row->nazev_en,
			'nazev_de' => $row->nazev_de,
			'nazev_sk' => $row->nazev_sk,
			'obsah' => $row->obsah,
			'obsah_en' => $row->obsah_en,
			'obsah_de' => $row->obsah_de,
			'obsah_sk' => $row->obsah_sk,
			'url' => $row->url,
			'foto' => $row->foto,
			'ikona' => $row->ikona,
			'h1' => $row->h1,
			'pod' => $row->pod
		);
		return $data;
	}

	// Uloží email pro odběr novinek
	public function saveEmail($email, $uid)
	{

		$email = $this->sqlInjection($email);
		$uid = $this->sqlInjection($uid);

		$sql = "SELECT email FROM " . $this->prefix . "newsletter WHERE email='$email'";
		$result = $this->db()->query($sql);

		if (!$result->num_rows) {

			$sql = "INSERT INTO " . $this->prefix . "newsletter SET email='$email', uid='$uid'";
			$this->db()->query($sql);
			return true;
		} else {
			return false;
		}

	}

	// Vypíše novinky
	public function getNews()
	{
		$sql = "SELECT id, nazev, nazev_en, nazev_de, nazev_sk, perex, perex_en, perex_de, perex_sk, obsah, obsah_de, obsah_en, obsah_sk, foto, foto_en, foto_de, foto_sk, url, datum FROM " . $this->prefix . "novinky WHERE aktivni = '1' ORDER BY poradi ASC";
		$result = $this->db()->query($sql);

		return $result;
	}

	// Vypíše detail novinky
	public function getNewsDetail($url)
	{
		$url = $this->sqlInjection($url);
		$sql = "SELECT id, nazev, nazev_en, nazev_de, nazev_sk, perex, perex_en, perex_de, perex_sk, obsah, obsah_de, obsah_en, obsah_sk, foto, foto_en, foto_de, foto_sk, url, datum FROM " . $this->prefix . "novinky WHERE url = '$url'";
		$result = $this->db()->query($sql);

		return $result;
	}

	// Stáhne kurzovni listek - nutné nastavit cron
	public function downloadCNB()
	{

		$date = date('d.m.Y', time());
		$feed = file_get_contents("http://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/denni_kurz.txt?date=$date");

		if ($feed == TRUE) {
			$file = fopen("./kurzy.txt", "w");
			fwrite($file, $feed);
			fclose($file);
		}

	}

	// Vrátí hodnotu kurzu
	public function cndFeed($currency)
	{
		$date = date('d.m.Y', time());
		$feed = file_get_contents("./kurzy.txt");
		$feed = explode('|', $feed);
		foreach ($feed as $key => $val) {
			if ($val == $currency) {
				$k = $key + 1;
				break;
			}
		}
		$kurz = explode(PHP_EOL, $feed[$k]);
		$value = $kurz[0];
		$value = str_replace(',', '.', $value);

		return $value;
	}

	// Nastavení webu
	public function getOptions($id)
	{
		$id = $this->sqlInjection($id);

		$sql = "SELECT value FROM " . $this->prefix . "options WHERE id='$id'";
		$result = $this->db()->query($sql);
		$row = mysqli_fetch_object($result);

		return $row->value;
	}

	// Fotogalerie
	public function getGalleries()
	{

		$sql = "SELECT 
		" . $this->prefix . "fotogalerie.id, 
		" . $this->prefix . "fotogalerie.nazev, 
		" . $this->prefix . "fotogalerie.url, 
		" . $this->prefix . "fotogalerie_media.foto 
		FROM 
		" . $this->prefix . "fotogalerie 
		LEFT JOIN 
		" . $this->prefix . "fotogalerie_media 
		ON 
		" . $this->prefix . "fotogalerie.id = " . $this->prefix . "fotogalerie_media.id_gallery 
		WHERE 
		" . $this->prefix . "fotogalerie.aktivni AND (" . $this->prefix . "fotogalerie_media.poradi='1' OR " . $this->prefix . "fotogalerie_media.poradi='0')
		GROUP BY 
		" . $this->prefix . "fotogalerie.id 
		ORDER BY 
		" . $this->prefix . "fotogalerie.poradi ASC";

		$result = $this->db()->query($sql);

		return $result;

	}

	// Reference
	public function getReferences($id = null,$limit = null)
	{
		$cond = '';
		$cond2 = '';
		if($limit){
			$cond = 'LIMIT '.$limit;
		}


		if($id){

			$id = $this->sqlInjection($id);
			$cond2 =  $this->prefix . "reference.snippet = '$id' AND ";

		}

		$sql = "SELECT 
		" . $this->prefix . "reference.id, 
		" . $this->prefix . "reference.nazev, 
		" . $this->prefix . "reference.perex, 
		" . $this->prefix . "reference.url, 
		" . $this->prefix . "reference_media.foto 
		FROM 
		" . $this->prefix . "reference 
		LEFT JOIN 
		" . $this->prefix . "reference_media 
		ON 
		" . $this->prefix . "reference.id = " . $this->prefix . "reference_media.id_reference 
		WHERE 
		$cond2 " . $this->prefix . "reference.aktivni AND (" . $this->prefix . "reference_media.poradi = '1' OR " . $this->prefix . "reference_media.poradi = '0')
		GROUP BY 
		" . $this->prefix . "reference.id 
		ORDER BY 
		" . $this->prefix . "reference.poradi ASC $cond";

		$result = $this->db()->query($sql);

		return $result;

	}

	// Reference
	public function getReferencesDetail($url)
	{
		$url = $this->sqlInjection($url);

		$sql = "SELECT 
		" . $this->prefix . "reference.id, 
		" . $this->prefix . "reference.nazev, 
		" . $this->prefix . "reference.obsah, 
		" . $this->prefix . "reference.perex, 
		" . $this->prefix . "reference_media.foto 
		FROM 
		" . $this->prefix . "reference 
		LEFT JOIN 
		" . $this->prefix . "reference_media 
		ON 
		" . $this->prefix . "reference.id = " . $this->prefix . "reference_media.id_reference 
		WHERE 
		" . $this->prefix . "reference.url = '$url' AND " . $this->prefix . "reference.aktivni AND (" . $this->prefix . "reference_media.poradi = '1' OR " . $this->prefix . "reference_media.poradi = '0')";

		$result = $this->db()->query($sql);
		$row = mysqli_fetch_array($result);
		return $row;

	}


	// Detail fotogalerie
	public function getGalleryDetail($url)
	{
		$url = $this->sqlInjection($url);

		$sql = "SELECT 
		" . $this->prefix . "fotogalerie.id, 
		" . $this->prefix . "fotogalerie.nazev, 
		" . $this->prefix . "fotogalerie.obsah
	
		FROM 
		" . $this->prefix . "fotogalerie 

		WHERE 
		" . $this->prefix . "fotogalerie.url = '$url' AND " . $this->prefix . "fotogalerie.aktivni";

		$result = $this->db()->query($sql);
		$row = mysqli_fetch_array($result);
		return $row;

	}

	// Detail fotogalerie
	public function getGalleryPhotos($id, $limit=null)
	{
		$limit_cond = '';
		if ($limit) {
			$limit_cond = 'LIMIT ' . $limit;
		}

		$id = $this->sqlInjection($id);
		$sql = "SELECT foto, nazev FROM " . $this->prefix . "fotogalerie_media WHERE id_gallery = '$id' ORDER BY poradi ASC $limit_cond";
		$result = $this->db()->query($sql);
		return $result;
	}

	// Detail fotogalerie
	public function getReferencesPhotos($id)
	{

		$id = $this->sqlInjection($id);
		$sql = "SELECT foto, nazev FROM " . $this->prefix . "reference_media WHERE id_reference = '$id' ORDER BY poradi ASC";
		$result = $this->db()->query($sql);
		return $result;
	}

	public function getNextPrevReference(){

		$sql = "SELECT id,url FROM ".$this->prefix."reference WHERE aktivni ORDER BY poradi ASC";
		$result = $this->db()->query($sql);
		while($row = mysqli_fetch_object($result)){
			$data[] = $row->url;
		}

		return $data;

	}

}
