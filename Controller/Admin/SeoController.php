<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SeoBundle\Controller\Admin;

use Mindy\Bundle\MindyBundle\Controller\Controller;
use Mindy\Bundle\PaginationBundle\Utils\PaginationTrait;
use Mindy\Bundle\SeoBundle\Form\SeoForm;
use Mindy\Bundle\SeoBundle\Model\Seo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SeoController extends Controller
{
    use PaginationTrait;

    public function list(Request $request)
    {
        $qs = Seo::objects();
        $pager = $this->createPagination($qs);

        return $this->render('admin/seo/seo/list.html', [
            'seos' => $pager->paginate(),
            'pager' => $pager->createView(),
        ]);
    }

    public function create(Request $request)
    {
        $seo = new Seo();

        $form = $this->createForm(SeoForm::class, $seo, [
            'method' => 'POST',
            'action' => $this->generateUrl('admin_seo_seo_create'),
        ]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $seo = $form->getData();
            if (false === $seo->save()) {
                throw new \RuntimeException('Error while save seo');
            }

            $this->addFlash('success', 'Мета информация успешно сохранена');

            return $this->redirectToRoute('admin_seo_seo_list');
        }

        return $this->render('admin/seo/seo/create.html', [
            'form' => $form->createView(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $seo = Seo::objects()->get(['id' => $id]);
        if (null === $seo) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(SeoForm::class, $seo, [
            'method' => 'POST',
            'action' => $this->generateUrl('admin_seo_seo_update', ['id' => $id]),
        ]);

        if ($form->handleRequest($request) && $form->isValid()) {
            $seo = $form->getData();
            if (false === $seo->save()) {
                throw new \RuntimeException('Error while save seo');
            }

            $this->addFlash('success', 'Мета информация успешно сохранена');

            return $this->redirectToRoute('admin_seo_seo_list');
        }

        return $this->render('admin/seo/seo/update.html', [
            'form' => $form->createView(),
            'seo' => $seo,
        ]);
    }

    public function remove(Request $request, $id)
    {
        $menu = Seo::objects()->get(['id' => $id]);
        if (null === $menu) {
            throw new NotFoundHttpException();
        }

        $menu->delete();

        $this->addFlash('success', 'Мета информация успешно удалена');

        return $this->redirectToRoute('admin_seo_seo_list');
    }
}
