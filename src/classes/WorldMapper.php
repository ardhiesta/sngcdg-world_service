<?php
class WorldMapper extends Mapper{
	public function getCountries(){
        $sql = "SELECT Name, Continent, Region from country";
				// $sql = "SELECT * from country";
        $stmt = $this->db->query($sql);
        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = array_map('utf8_encode', $row);
        }
        return $results;
    }

		public function getCountriesPaging($starts_record, $records_per_page){
        $sql = "SELECT Name, Continent, Region from country limit ".$starts_record.", ".$records_per_page;
        $stmt = $this->db->query($sql);
        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = array_map('utf8_encode', $row);
        }

        return $results;
    }
}
