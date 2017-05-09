<?php

namespace Mixdinternet\Testimonials\Http\Controllers;

use App\Http\Controllers\Controller;
use Mixdinternet\Testimonials\Testimonial;
use Illuminate\Http\Request;

class TestimonialsController extends Controller
{
    protected $fields = ['id', 'star', 'name', 'description', 'published_at', 'slug', 'image_file_name'];

    public function index(Request $request)
    {
        $limit = $request->get('limit', 5);

        return Testimonial::active()->paginate($limit, $this->fields)
            ->addQuery('limit', $limit);
    }

    public function show($slug)
    {
        return Testimonial::select($this->fields)->active()->where('slug', $slug)->first();
    }
}
