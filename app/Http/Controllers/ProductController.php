<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    
    public function getProducts()
    {
        $products = Product::all();

        return response()->json([
            'status' => 200,
            'products' => $products,
        ]);
    }



    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'price'=> 'required',
            'quantity' => 'required',
            'category' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if($validator->fails()){

            return response()->json([
                'validation_err' => $validator->messages(),
            ]);

        }else{

            $product = new Product;
            $product->name = $request->name;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->category = $request->category;
            $product->description = $request->description;

            if($request->hasFile('image'))
            {
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $image_name = time(). '.' .$extension;
                $image->move('uploads/images', $image_name);
                $product->image = 'uploads/images/' . $image_name;
            }

            $product->save();
    
            return response()->json([
                'status' => 200,
                'message' => "Product added successfully",
            ]);
        }
    }



    public function getProduct($id)
    {
        $product = Product::find($id);

        if($product){

            return response()->json([
                'status' => 200,
                'product' => $product,
            ]);

        }else{

            return response()->json([
                'status' => 404,
                'message' => 'Product not found!',
            ]);
        }
    }



    public function updateProduct(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'price'=> 'required',
            'quantity' => 'required',
            'description' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);


        if($validator->fails()){

            return response()->json([
                'status' => 422,
                'validation_err' => $validator->messages(),
            ]);

        }else{

            $product = Product::find($id);

            if($product){

                $product->name = $request->name;
                $product->price = $request->price;
                $product->quantity = $request->quantity;
                $product->description = $request->description;
    
                // if($request->hasFile('image'))
                // {
                //     $path = $product->image;
                //     if(File::exists($path)){
                //         File::delete($path);
                //     }
                //     $image = $request->file('image');
                //     $extension = $image->getClientOriginalExtension();
                //     $image_name = time(). '.' .$extension;
                //     $image->move('uploads/images', $image_name);
                //     $product->image = 'uploads/images/' . $image_name;
                // }
    
                $product->update();
        
                return response()->json([
                    'status' => 200,
                    'message' => "Updated successully",
                ]);

            }else{

                return response()->json([
                    'status' => 404,
                    'message' => 'Product not found!',
                ]);
            }
        }
    }



    public function deleteProduct($id)
    {
        $product = Product::find($id);

        if($product){

            $product->delete();
    
            return response()->json([
                'status' => 200,
                'message' => 'Deleted successfully',
            ]);

        }else{

            return response()->json([
                'status' => 404,
                'message' => 'Product not found!',
            ]);
        }
    }



}
