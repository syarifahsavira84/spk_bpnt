<?php 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Produk;
use App\Karyawan;

class CreateInvoiceRequest extends FormRequest {

  public function authorize(){
    return true;
  }

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{

      $rules = [
         'type' => 'max:2|required|between:["21","23"]',
         'diskon' => 'nullable|max:3|regex:/^\s*[-+]?[0-9]*[.]?[0-9]+([eE][-+]?[0-9]+)?\s*$/',
         'discount_price' => 'nullable|numeric',
         'downpayment' => 'nullable|numeric',
         'package_price' => 'nullable|numeric',
         'print' => 'nullable|numeric',
         'package' => 'nullable|numeric|exists:pakets,id_paket',
         'note' => 'nullable|max:255',
         'consumerName' => 'max:64',
         'consumerEmail' => 'nullable|email',
         'consumerHp' => 'nullable|numeric',
         'consumerAddress' => 'nullable|string|max:150',
         'product' => ['nullable', function($attribute, $value, $fail){
            if($value != '' && is_array($value)){
              foreach($value as $e){
                if(isset($e['name'])){
                  if($e['name'] != ''){
                    $queryProduct = Produk::where('id_produk', '=', $e['name'])->first();
                    if(!$queryProduct){
                      $fail('Produk tidak ditemukan');
                    }else{
                      if(isset($e['qty']) && $e['qty'] == ''){
                        $fail($queryProduct->nama_produk. ' quantity tidak boleh kosong');
                      }
                      if(isset($e['qty']) && !is_numeric($e['qty'])){
                        $fail($queryProduct->nama_produk . ' quantity tidak valid');
                      }
                      if(isset($e['price']) && $e['price'] == ''){
                        $fail($queryProduct->nama_produk . ' Harga tidak boleh kosong');
                      }
                    }
                  }else{
                    $fail('Oops..!!!, Tampanya ada kesalahan, silahkan muat ulang laman.');
                  }
                }
              }
            }
         }],
         'teknisi' => ['nullable', function($attribute, $value, $fail){
              if($value != '' && is_array($value)){
                foreach($value as $e){
                  if($e != ''){
                    $queryProduct = Karyawan::where('id_karyawan', '=', $e)->where('jabatan' , '=', 2)->first();
                    if(!$queryProduct){
                      $fail('Teknisi tidak ditemukan');
                    }   
                  }
                }
              }
           }],
         'pic' => ['nullable', function($attribute, $value, $fail){
              if($value != '' && is_array($value)){
                foreach($value as $e){
                  if($e != ''){
                    $queryProduct = Karyawan::where('id_karyawan', '=', $e)->where('jabatan' , '=', 1)->first();
                    if(!$queryProduct){
                      $fail('Teknisi tidak ditemukan');
                    }  
                  }
                }
              }
           }],
           
      ];

      return $rules;

	}

}