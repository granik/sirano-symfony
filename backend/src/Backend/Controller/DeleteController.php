<?php


namespace App\Backend\Controller;


use Symfony\Component\HttpFoundation\Request;

trait DeleteController
{
    public function delete(Request $request)
    {
        if (!$request->request->has('id')) {
            return $this->json([
                'status' => 'error',
                'error'  => 'required parameter missing',
            ]);
        }
        
        $this->interactor->delete($request->request->get('id'));
        
        return $this->json(['status' => 'ok']);
    }
    
}