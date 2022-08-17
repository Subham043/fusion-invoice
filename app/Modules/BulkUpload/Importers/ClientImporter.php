<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\BulkUpload\Importers;

use FI\Modules\Clients\Models\Client;

class ClientImporter extends MainImporter
{

	public function importData()
        {
		
		if(!empty($this->obj[0]) && !empty($this->obj[13]) && !empty($this->obj[9]) && !empty($this->obj[10]) && preg_match('/^[a-zA-Z0-9\s\-\_]*$/', $this->obj[0])){

			$client = Client::where('unique_name',$this->obj[13])->where('name',$this->obj[0])->get();
			if(count($client)>0){
				$inv = array(
				"name"=>$this->obj[0],
				"type"=>$this->obj[1],
				"address"=>$this->obj[2],
				"city"=>$this->obj[3],
				"state"=>$this->obj[4],
				"zip"=>$this->obj[5],
				"country"=>$this->obj[6],
				"phone"=>$this->obj[7],
				"fax"=>$this->obj[8],
				"mobile"=>$this->obj[9],
				"email"=>$this->obj[10],
				"web"=>$this->obj[11],
				"active"=>$this->obj[12],
				"unique_name"=>$this->obj[13],
				"special"=>$this->obj[14],
				);

				$client = Client::where('unique_name',$this->obj[13])->where('name',$this->obj[0])->first();
				$client->fill($inv);
        			$client->save();

				return $client;
			}else{
				$inv = array(
				"name"=>$this->obj[0],
				"type"=>$this->obj[1],
				"address"=>$this->obj[2],
				"city"=>$this->obj[3],
				"state"=>$this->obj[4],
				"zip"=>$this->obj[5],
				"country"=>$this->obj[6],
				"phone"=>$this->obj[7],
				"fax"=>$this->obj[8],
				"mobile"=>$this->obj[9],
				"email"=>$this->obj[10],
				"web"=>$this->obj[11],
				"active"=>$this->obj[12],
				"unique_name"=>$this->obj[13],
				"special"=>$this->obj[14],
				);
				$inventory = Client::create($inv);
				return $inventory;
			}
		}

	}

}