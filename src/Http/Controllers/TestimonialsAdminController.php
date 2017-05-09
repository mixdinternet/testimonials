<?php

namespace Mixdinternet\Testimonials\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Flash\Facades\Flash;
use Mixdinternet\Admix\Http\Controllers\AdmixController;
use Mixdinternet\Testimonials\Testimonial;
use Mixdinternet\Testimonials\Http\Requests\CreateEditTestimonialsRequest;

class TestimonialsAdminController extends AdmixController
{
    public function index(Request $request)
    {
        session()->put('backUrl', request()->fullUrl());

        $trash = ($request->segment(3) == 'trash') ? true : false;

        $query = Testimonial::sort();
        ($trash) ? $query->onlyTrashed() : '';

        $search = [];
        $search['status'] = $request->input('status', '');
        $search['star'] = $request->input('star', '');
        $search['name'] = $request->input('name', '');

        ($search['status']) ? $query->where('status', $search['status']) : '';
        ($search['star'] != '') ? $query->where('star', $search['star']) : '';
        ($search['name']) ? $query->where('name', 'LIKE', '%' . $search['name'] . '%') : '';

        $testimonials = $query->paginate(50);

        $view['trash'] = $trash;
        $view['search'] = $search;
        $view['testimonials'] = $testimonials;

        return view('mixdinternet/testimonials::admin.index', $view);
    }

    public function create(Testimonial $testimonial)
    {
        $view['testimonial'] = $testimonial;

        return view('mixdinternet/testimonials::admin.form', $view);
    }

    public function store(CreateEditTestimonialsRequest $request)
    {
        if (Testimonial::create($request->all())) {
            Flash::success('Item inserido com sucesso.');
        } else {
            Flash::error('Falha no cadastro.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.testimonials.index');
    }

    public function edit(Testimonial $testimonial)
    {
        $view['testimonial'] = $testimonial;

        return view('mixdinternet/testimonials::admin.form', $view);
    }

    public function update(Testimonial $testimonial, CreateEditTestimonialsRequest $request)
    {
        $input = $request->all();

        if (isset($input['remove'])) {
            foreach ($input['remove'] as $k => $v) {
                $testimonial->{$v}->destroy();
                $testimonial->{$v} = STAPLER_NULL;
            }
        }

        if ($testimonial->update($input)) {
            Flash::success('Item atualizado com sucesso.');
        } else {
            Flash::error('Falha na atualização.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.testimonials.index');
    }

    public function destroy(Request $request)
    {
        if (Testimonial::destroy($request->input('id'))) {
            Flash::success('Item removido com sucesso.');
        } else {
            Flash::error('Falha na remoção.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.testimonials.index');
    }

    public function restore($id)
    {
        $testimonial = Testimonial::onlyTrashed()->find($id);

        if (!$testimonial) {
            abort(404);
        }

        if ($testimonial->restore()) {
            Flash::success('Item restaurado com sucesso.');
        } else {
            Flash::error('Falha na restauração.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.testimonials.trash');
    }
}
