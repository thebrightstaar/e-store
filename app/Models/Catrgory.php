<?php

namespace App\Models;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Kalnoy\Nestedset\NodeTrait;


class Catrgory extends Model
{
    use HasFactory;
        use NodeTrait;

     protected $with = ['translations'];
    protected $translatedAttributes = ['catrgory_name','up_name'];
    protected $fillable = ['catrgory_name', 'up_name','parent_id','is_active'];

    protected $casts = [
         'is_active' => 'boolean',
    ];

    protected $hidden = ['translations'];


   public function getRootCategory(): Category
    {
        return Category::where([
            ['parent_id', '=', null],
            ['_lft', '<=', $this->_lft],
            ['_rgt', '>=', $this->_rgt],
        ])->first();
    }

    public function getPathCategories(): array
    {
        $category = $this->findInTree();

        $categories = [$category];

        while (isset($category->parent)) {
            $category = $category->parent;
            $categories[] = $category;
        }

        return array_reverse($categories);
    }

    public function findInTree($categoryTree = null): Category
    {
        if (! $categoryTree) {
            $categoryTree = getVisibleCategoryTree($this->getRootCategory()->id);
        }

        $category = $categoryTree->first();

        if (! $category) {
            return'category not found in tree';
        }

        if ($category->id === $this->id) {
            return $category;
        }

        return $this->findInTree($category->children);
    }

    public function scopeParent($query){
        return $query -> whereNull('parent_id');
    }
    public function scopeChild($query){
        return $query -> whereNotNull('parent_id');
    }

    public function getActive(){
       return  $this -> is_active  == 0 ?  'disenabled '   :' enabled ' ;
    }

   
    public function scopeActive($query){
        return $query -> where('is_active',1) ;
    }

    //get all childrens=
    public function childrens(){
        return $this -> hasMany(Catrgory::class,'parent_id');
    }

    public function products()
    {
        return $this -> belongsToMany(Product::class,'product_categories');
    }



    
}
