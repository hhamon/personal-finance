<?php

namespace App\Controller\Backend\User;

use App\Controller\Backend\BaseController;
use App\Entity\User\User;
use App\Form\Filter\UserFilterType;
use App\Form\Model\ChangePassword;
use App\Form\User\ChangePasswordType;
use App\Form\User\UserType;
use App\Repository\User\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/backend/users")
 */
class UserController extends BaseController {
    /**
     * @Route("/", name="backend_user_index", methods={"GET"})
     * @param UserRepository $userRepository
     * @param Request $request
     * @return Response
     */
    public function index(UserRepository $userRepository, Request $request): Response {
        // Filtering
        $filterForm = $this->createForm(UserFilterType::class);

        /** @var User[] $users */
        $users = $this->paginateWithFiltering($userRepository, $request, $filterForm, [
            'defaultSortFieldName' => 'o.creationTime',
            'defaultSortDirection' => 'desc'
        ]);

        return $this->render('backend/user/index.html.twig', [
            'users' => $users,
            'filterForm' => $filterForm->createView(),
        ]);
    }

    /**
     * @Route("/new", name="backend_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['allow_change_roles' => $this->isGranted("ROLE_SUPER_ADMIN")]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('backend_user_index');
        }

        return $this->render('backend/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_user_show", methods={"GET"})
     */
    public function show(User $user): Response {
        return $this->render('backend/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response {
        $form = $this->createForm(UserType::class, $user, ['allow_change_roles' => $this->isGranted("ROLE_SUPER_ADMIN")]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('backend/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_user_delete", methods={"DELETE"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function delete(Request $request, User $user, TranslatorInterface $translator): Response {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            if($user === $this->getUser()){
                $this->addFlash("error", $translator->trans('You can not delete your own user.'));
                return $this->redirectToRoute("backend_user_index");
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_user_index');
    }

    /**
     * Changes current user password
     * @Route("/backend/user/change-password", name="backend_user_change_password")
     */
    public function changePassword(Request $request, TranslatorInterface $translator) {
        $changePasswordModel = new ChangePassword();

        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);
        $form->handleRequest($request);

        /**
         * @var $user User
         */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("success", $translator->trans("Your password was successfully changed."));
            $changePasswordModel->update($user);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_dashboard');
        }

        return $this->render('backend/user/change_password.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
