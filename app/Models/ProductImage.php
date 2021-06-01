<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;


class ProductImage extends Model 
{
    public $timestamps = false;

    protected $fillable = [
        'path',
        'photo',
        'product_id',
    ];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(Product::Class());
    }

    /**
     * Get image url for the product image.
     */
    public function url()
    {
        return Storage::url($this->path);
    }

    public function toArray()
    {
        $array = parent::toArray();

        $array['url'] = $this->url;

        return $array;
    }

     public function uploadImages($data, $product)
    {
        $previousImageIds = $product->images()->('id');

        if (isset($data['images'])) {
            foreach ($data['images'] as $imageId => $image) {
                $file = 'images.' . $imageId;
                $dir = 'product/' . $product->id;

                if ($image instanceof UploadedFile) {
                    if (request()->hasFile($file)) {
                        $this->create([
                            'path'       => request()->file($file)->store($dir),
                            'product_id' => $product->id,
                        ]);
                    }
                } else {
                    if (is_numeric($index = $previousImageIds->search($imageId))) {
                        $previousImageIds->forget($index);
                    }
                }
            }
        }

        foreach ($previousImageIds as $imageId) {
            if ($imageModel = $this->find($imageId)) {
                Storage::delete($imageModel->path);

                $this->delete($imageId);
            }
        }
    }
}