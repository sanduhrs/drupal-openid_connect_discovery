<?php

namespace Drupal\openid_connect_discovery\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns responses for OpenID Connect Discovery routes.
 */
class OpenidConnectDiscoveryController extends ControllerBase {

  /**
   * The OpenID Connect configuration endpoint.
   *
   * @see https://openid.net/specs/openid-connect-discovery-1_0.html#ProviderMetadata
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A json response object.
   */
  public function configuration(): JsonResponse {
    $data = [
      'issuer' => Url::fromUri('internal:/')->setAbsolute()->toString(),
      'authorization_endpoint' => Url::fromRoute('oauth2_token.authorize')->setAbsolute()->toString(),
      'token_endpoint' => Url::fromRoute('oauth2_token.token')->setAbsolute()->toString(),
      'userinfo_endpoint' => Url::fromRoute('simple_oauth.userinfo')->setAbsolute()->toString(),
      'jwks_uri' => Url::fromRoute('simple_oauth.jwks')->setAbsolute()->toString(),
      'end_session_endpoint' => Url::fromRoute('user.logout')->setAbsolute()->toString(),
      'response_types_supported' => ['code', 'id_token'],
      'subject_types_supported' => ['pairwise', 'public'],
      'id_token_signing_alg_values_supported' => ['RS256'],
      'scopes_supported' => ['openid', 'profile', 'email'],
    ];

    if (\Drupal::moduleHandler()->moduleExists('openid_connect_dynamic_registration')) {
      $data['registration_endpoint'] = (new Url('openid_connect_dynamic_registration.registration'))->setAbsolute()->toString();
    }
    return new JsonResponse($data);
  }

}
