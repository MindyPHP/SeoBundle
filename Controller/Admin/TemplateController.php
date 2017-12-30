<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Controller\Admin;

use Mindy\Bundle\MindyBundle\Controller\Controller;
use Mindy\Bundle\PaginationBundle\Utils\PaginationTrait;
use Mindy\Bundle\SeoBundle\Form\TemplateForm;
use Mindy\Bundle\SeoBundle\Model\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TemplateController extends Controller
{
    use PaginationTrait;

    public function list(Request $request)
    {
        $templates = Template::objects()->all();

        return $this->render('admin/seo/template/list.html', [
            'templates' => $templates,
        ]);
    }

    public function create(Request $request)
    {
        $template = new Template();

        $form = $this->createForm(TemplateForm::class, $template, [
            'method' => 'POST',
            'action' => $this->generateUrl('admin_rise_seo_template_create'),
        ]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $template = $form->getData();
            if (false === $template->save()) {
                throw new \RuntimeException('Error while save template');
            }

            $this->addFlash('success', 'Шаблон успешно сохранен');

            return $this->redirectToRoute('admin_rise_seo_template_list');
        }

        return $this->render('admin/seo/template/create.html', [
            'form' => $form->createView(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $template = Template::objects()->get(['id' => $id]);
        if (null === $template) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(TemplateForm::class, $template, [
            'method' => 'POST',
            'action' => $this->generateUrl('admin_rise_seo_template_update', ['id' => $id]),
        ]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $template = $form->getData();
            if (false === $template->save()) {
                throw new \RuntimeException('Error while save template');
            }

            $this->addFlash('success', 'Шаблон успешно сохранен');

            return $this->redirectToRoute('admin_rise_seo_template_list');
        }

        return $this->render('admin/seo/template/update.html', [
            'form' => $form->createView(),
            'template' => $template,
        ]);
    }

    public function remove(Request $request, $id)
    {
        $menu = Template::objects()->get(['id' => $id]);
        if (null === $menu) {
            throw new NotFoundHttpException();
        }

        $menu->delete();

        $this->addFlash('success', 'Шаблон успешно удален');

        return $this->redirectToRoute('admin_rise_seo_template_list');
    }
}
