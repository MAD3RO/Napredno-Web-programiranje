<?php

interface iRadovi{
	public function create($redni_broj);
	public function save();
	public function read();
}

class DiplomskiRadovi implements iRadovi{

	var $naziv_rada;
	var $tekst_rada;
	var $link_rada;
	var $oib_tvrtke;
	var $redni_broj;

	function create($redni_broj)
	{
		$url = "http://stup.ferit.hr/zavrsni-radovi/page/" . $redni_broj; // definiranje URL-a
		$curl = curl_init($url); // inicijalizacija cURL-a za spajanje na stranicu

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$r = curl_exec($curl);
		$r_array = array();
		$buff = array();


		// dohvacanje podataka
        preg_match_all('!<img width="320" height="202" src="http:\/\/stup.ferit.hr\/wp-content\/logos\/(.*?).png"!', $r, $match);
        $r_array['oib_tvrtke'] = $match[1];

        preg_match_all('!<a href="http:\/\/stup.ferit.hr\/2018\/(.*?)"!', $r, $match);
        $buff = array_unique($match[1]);
        $r_array['link_rada'] = array_values($buff);

        preg_match_all('!<a href="http:\/\/stup.ferit.hr\/2018\/(.*?)\/(.*?)\/(.*?)\/"!', $r, $match);
        $buff = array_unique($match[3]);
        $r_array['tekst_rada'] = array_values($buff);

        preg_match_all('!<a href="http:\/\/stup.ferit.hr\/2018\/(.*?)">(.*?)<\/a>!', $r, $match);
		$r_array['naziv_rada'] = $match[2];

        $this->oib_tvrtke = $r_array['oib_tvrtke'];
        for ($i = 0; $i < count($r_array['link_rada']); $i++)
        {
            $r_array['link_rada'][$i] = "http://stup.ferit.hr/2018/" . $r_array['link_rada'][$i];
        }

		$this->link_rada = $r_array['link_rada'];
        $this->naziv_rada = $r_array['naziv_rada'];
        $this->tekst_rada = $r_array['tekst_rada'];

        curl_close($curl);
    }

    function save()
    {
        $db = mysqli_connect("localhost", "root", "", "radovi") or die("Can't connect to the desired database");

        for($i = 0; $i < count($this->oib_tvrtke); $i++)
        {
            $oib_tvrtke = $this->oib_tvrtke[$i];
            $link_rada = $this->link_rada[$i];
            $naziv_rada = $this->naziv_rada[$i];
            $tekst_rada = $this->tekst_rada[$i];

            $query = "INSERT INTO diplomski_radovi (oib_tvrtke, link_rada, naziv_rada, tekst_rada) VALUES ('$oib_tvrtke', '$link_rada', '$naziv_rada', '$tekst_rada')";
            mysqli_query($db, $query);       

        }           
    }

    function read()
    {			
        $db = mysqli_connect("localhost", "root", "", "radovi") or die("Can't connect to the desired database");
        $query = "SELECT oib_tvrtke, link_rada, naziv_rada, tekst_rada FROM diplomski_radovi" or die("Query error");
        $r_array = mysqli_query($db, $query);
        return $r_array;
    }
}

?>