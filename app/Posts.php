<?php
/*
 * @Author: zhangtao 
 * @Date: 2017-12-04 15:55:48 
 * @Last Modified by: DingBing
 * @Last Modified time: 2017-12-14 10:26:26
 */
namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Config;
use zgldh\QiniuStorage;
use Illuminate\Http\Request;

class Posts extends Model
{
    const STATUS_PUBLISH  = 'PUBLISH'; // 已发布
    const STATUS_DRAFT    = 'DRAFT';   // 草稿
    protected $primaryKey = 'post_id';
    protected $fillable   = ['title','cat_id','author','status','is_allow','is_page','markdown','html'];
    /**
     * get article list
     *
     * @param [type] $type   is PUBLISH or DRAFT
     * @param string $title  has the search where
     * @param string $limit  has the limit
     * @return void
     */
    static public function getArticleList($type, $title = '', $limit = '')
    {
        $query = self::select('post_id','title','author','cat_id','read_num','updated_at','status', 'html')
            ->where(['status' => $type]);
        if(!empty($title))
        {
           $query = $query->orWhere('title', 'like', "%$title%");
        }
        if(!empty($limit))
        {
            $query = $query->limit($limit);
        }
        $arr = $query->orderBy('post_id', 'desc')->paginate(Config::get('constants.page_size'));
        return $arr;
    }

    /**
     * 分类的关联
     *
     * @return void
     */
    public function cat()
    {
        return $this->belongsTo('App\Categories', 'cat_id', 'cat_id');
    }
    /**
     * 获取一条
     * @param  [type] $where [description]
     * @return [type]        [description]
     */
    public function getOne($where){
        return $this->select('post_id','title','author','cat_id','read_num','like_num','updated_at','status', 'html')
            ->where($where)->first();
    }
    /**
     * 获取对应的文章名称
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function getPost($data)
    {
        foreach ($data as $key => $value) {
            $title=$this->getOne(['post_id'=>$value->post_id]);
            $data[$key]->title=$title->title;
        }
        return $data;        
    }

   

}