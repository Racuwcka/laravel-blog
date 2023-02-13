<?php

namespace App\Services;

use App\Models\Post;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;

class PostService
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            if (isset($data['tag_ids'])) {
                $tagIds = $data['tag_ids'];
                unset($data['tag_ids']);
            }
            if (isset($data['preview_image'])) {
//                $data['preview_image'] = Storage::disk('public')->put('/images', $data['preview_image']);
                $data['preview_image'] = Cloudinary::upload($data['preview_image']->getRealPath(),[
                    'folder' => 'Laravel-blog/preview'
                ])->getSecurePath();
            }
            if (isset($data['main_image'])) {
//                $data['main_image'] = Storage::disk('public')->put('/images', $data['main_image']);
                $data['main_image'] = Cloudinary::upload($data['main_image']->getRealPath(),[
                    'folder' => 'Laravel-blog/main'
                ])->getSecurePath();
            }
            $post = Post::firstOrCreate($data);
            if (isset($tagIds)) {
                $post->tags()->sync($tagIds);
            }
            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }

    public function update($data, $post)
    {
        try {
            DB::beginTransaction();
            if (isset($data['tag_ids'])) {
                $tagIds = $data['tag_ids'];
                unset($data['tag_ids']);
            }
            if (isset($data['preview_image'])) {
                $data['preview_image'] = Cloudinary::upload($data['preview_image']->getRealPath(),[
                    'folder' => 'Laravel-blog/preview'
                ])->getSecurePath();
//                $data['preview_image'] = Storage::disk('public')->put('/images', $data['preview_image']);
            }
            if (isset($data['main_image'])) {
                $data['main_image'] = Cloudinary::upload($data['main_image']->getRealPath(),[
                    'folder' => 'Laravel-blog/main'
                ])->getSecurePath();
//                $data['main_image'] = Storage::disk('public')->put('/images', $data['main_image']);
            }
            $post->update($data);
            if (!empty($tagIds)) {
                $post->tags()->sync($tagIds);
            } else {
                $post->tags()->detach();
            }
            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
        return $post;
    }
}
