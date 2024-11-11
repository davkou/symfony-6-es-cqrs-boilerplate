<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\User;

use App\User\Application\Command\SignIn\SignInCommand;
use App\User\Application\Command\SignUp\SignUpCommand;
use Assert\Assertion;
use Assert\AssertionFailedException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use UI\Http\Rest\Controller\CommandController;
use UI\Http\Rest\Response\OpenApi;

final class SignInController extends CommandController
{
    /**
	 * @throws Throwable
	 * @throws AssertionFailedException
	 */
	#[OA\Response(
		response: Response::HTTP_ACCEPTED,
		description: 'User signed in successfully',
	)]
	#[OA\Response(
		response: Response::HTTP_UNAUTHORIZED,
		description: 'Unauthorized',
	)]
	#[OA\Response(
		response: Response::HTTP_CONFLICT,
		description: 'Conflict',
	)]
	#[OA\RequestBody(
		content: new OA\JsonContent(
			properties: [
				new OA\Property(property: 'email', type: 'string', format: 'email'),
				new OA\Property(property: 'password', type: 'string', format: 'string'),
			],
			type: 'object',
		)
	)]
	#[OA\Tag(name: 'User')]
	#[Route(path: '/signin', name: 'user_signin', methods: [Request::METHOD_POST])]
	public function __invoke(Request $request): OpenApi
	{
		$email = (string) $request->request->get('email');
		$plainPassword = (string) $request->request->get('password');

		Assertion::notEmpty($email, "Email can\'t be empty");
		Assertion::notEmpty($plainPassword, "Password can\'t be empty");

		$commandRequest = new SignInCommand($email, $plainPassword);

		$this->handle($commandRequest);

		return OpenApi::empty(202);
	}
}
