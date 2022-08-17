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

use FI\Modules\ItemLookups\Models\ItemLookup;
use FI\Modules\Inventory\Models\Inventory;
use FI\Modules\InventoryCategory\Models\InventoryCategory;
use FI\Modules\InventorySubCategory\Models\InventorySubCategory;
use FI\Modules\InventoryLocation\Models\InventoryLocation;
use FI\Modules\InventoryStyle\Models\InventoryStyle;
use FI\Modules\InventoryItemLocation\Models\InventoryItemLocation;



class InventoryImporter extends MainImporter
{

	public function importData()
        {

		if( !empty($this->obj[0]) && !empty($this->obj[2]) && !empty($this->obj[7]) ){

			if( !empty($this->obj[2]) ){
				$category = InventoryCategory::where('name',$this->obj[2])->get();
				if(count($category)==0){
					$category = InventoryCategory::create(array("name"=>$this->obj[2]));
					if( !empty($this->obj[3]) ){
						$subcategory = InventorySubCategory::where('name',$this->obj[3])->where('inventory_category_id',$category->id)->get();
						if(count($subcategory)==0){
							$subcategory = InventorySubCategory::create(array("name"=>$this->obj[3],"inventory_category_id"=>$category->id));
						}else{
							$subcategory = InventorySubCategory::where('name',$this->obj[3])->where('inventory_category_id',$category->id)->first();
							$subcategory->fill(array("name"=>$this->obj[3],"inventory_category_id"=>$category->id));
        						$subcategory->save();
						}
					}
				}else{
					$category = InventoryCategory::where('name',$this->obj[2])->first();
					$category->fill(array("name"=>$this->obj[2]));
        				$category->save();
					if( !empty($this->obj[3]) ){
						$subcategory = InventorySubCategory::where('name',$this->obj[3])->where('inventory_category_id',$category->id)->get();
						if(count($subcategory)==0){
							$subcategory = InventorySubCategory::create(array("name"=>$this->obj[3],"inventory_category_id"=>$category->id));
						}else{
							$subcategory = InventorySubCategory::where('name',$this->obj[3])->where('inventory_category_id',$category->id)->first();
							$subcategory->fill(array("name"=>$this->obj[3],"inventory_category_id"=>$category->id));
        						$subcategory->save();
						}
					}

				}

			}


			if( !empty($this->obj[4]) ){
				$category = InventoryLocation::where('name',$this->obj[4])->get();
				if(count($category)==0){
					$category = InventoryLocation::create(array("name"=>$this->obj[4]));
					if( !empty($this->obj[5]) ){
						$subcategory = InventoryItemLocation::where('name',$this->obj[5])->where('inventory_location_id',$category->id)->get();
						if(count($subcategory)==0){
							$subcategory = InventoryItemLocation::create(array("name"=>$this->obj[5],"inventory_location_id"=>$category->id));
						}else{
							$subcategory = InventoryItemLocation::where('name',$this->obj[5])->where('inventory_location_id',$category->id)->first();
							$subcategory->fill(array("name"=>$this->obj[5],"inventory_location_id"=>$category->id));
        						$subcategory->save();
						}
					}
				}else{
					$category = InventoryLocation::where('name',$this->obj[4])->first();
					$category->fill(array("name"=>$this->obj[4]));
        				$category->save();
					if( !empty($this->obj[5]) ){
						$subcategory = InventoryItemLocation::where('name',$this->obj[5])->where('inventory_location_id',$category->id)->get();
						if(count($subcategory)==0){
							$subcategory = InventoryItemLocation::create(array("name"=>$this->obj[5],"inventory_location_id"=>$category->id));
						}else{
							$subcategory = InventoryItemLocation::where('name',$this->obj[5])->where('inventory_location_id',$category->id)->first();
							$subcategory->fill(array("name"=>$this->obj[5],"inventory_location_id"=>$category->id));
        						$subcategory->save();
						}
					}

				}

			}

			if( !empty($this->obj[14]) ){
				$category = InventoryStyle::where('name',$this->obj[14])->get();
				if(count($category)==0){
					$category = InventoryStyle::create(array("name"=>$this->obj[14]));
				}else{
					$category = InventoryStyle::where('name',$this->obj[14])->first();
					$category->fill(array("name"=>$this->obj[14]));
        				$category->save();
				}

			}

			if(!empty($this->obj[0]) && preg_match('/^[a-zA-Z0-9\s\-\_]*$/', $this->obj[0])){
			$inventory = Inventory::where('name',$this->obj[0])->get();
			if(count($inventory)==0){

				$inv = array(
				"name"=>$this->obj[0],
				"description"=>$this->obj[1],
				"category"=>$this->obj[2],
				"sub-category"=>$this->obj[3],
				"location"=>$this->obj[4],
				"item-location"=>$this->obj[5],
				"total"=>$this->obj[6],
				"price"=> str_replace('$', '', $this->obj[7]),
				"purchase-price"=>str_replace('$', '', $this->obj[8]),
				"purchase-date"=>$this->obj[9],
				"height"=>$this->obj[10],
				"length"=>$this->obj[11],
				"width"=>$this->obj[12],
				"color"=>$this->obj[13],
				"style"=>$this->obj[14],
				);
				$inventory = Inventory::create($inv);
				return $inventory;

			}else{
				$inv = array(
				"name"=>$this->obj[0],
				"description"=>$this->obj[1],
				"category"=>$this->obj[2],
				"sub-category"=>$this->obj[3],
				"location"=>$this->obj[4],
				"item-location"=>$this->obj[5],
				"total"=>$this->obj[6],
				"price"=> str_replace('$', '', $this->obj[7]),
				"purchase-price"=>str_replace('$', '', $this->obj[8]),
				"purchase-date"=>$this->obj[9],
				"height"=>$this->obj[10],
				"length"=>$this->obj[11],
				"width"=>$this->obj[12],
				"color"=>$this->obj[13],
				"style"=>$this->obj[14],
				);
				$inventory = Inventory::where('name',$this->obj[0])->first();
				$inventory->fill($inv);
        			$inventory->save();

				return $inventory;

			}
			}

		}

	}

}