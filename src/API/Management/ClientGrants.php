<?php

namespace Auth0\SDK\API\Management;

use Auth0\SDK\Exception\CoreException;

/**
 * Class ClientGrants.
 * Handles requests to the Client Grants endpoint of the v2 Management API.
 *
 * @package Auth0\SDK\API\Management
 */
class ClientGrants extends GenericResource
{

    /**
     * Get all Client Grants, by page if desired.
     *
     * @param array        $params   Additional URL parameters to send:
     *      - "audience" to filter be a specific API audience identifier.
     *      - "client_id" to return an object.
     *      - "include_totals" to return an object.
     * @param null|integer $page     The page number, zero based.
     * @param null|integer $per_page The amount of entries per page.
     *
     * @return mixed
     *
     * @throws \Exception Thrown by the Guzzle HTTP client when there is a problem with the API call.
     */
    public function getAll(array $params = [], $page = null, $per_page = null)
    {
        if (null !== $page) {
            $params['page'] = abs(intval($page));
        }

        if (null !== $per_page) {
            $params['per_page'] = abs(intval($per_page));
        }

        return $this->apiClient->method('get')
            ->addPath('client-grants')
            ->withDictParams($params)
            ->call();
    }

    /**
     * Get Client Grants by audience.
     *
     * @param string       $audience API Audience to filter by.
     * @param null|integer $page     The page number, zero based.
     * @param null|integer $per_page The amount of entries per page.
     *
     * @return mixed
     *
     * @throws CoreException Thrown when $audience is empty or not a string.
     * @throws \Exception Thrown by the Guzzle HTTP client when there is a problem with the API call.
     */
    public function getByAudience($audience, $page = null, $per_page = null)
    {
        if (empty($audience) || ! is_string($audience)) {
            throw new CoreException('Empty or invalid "audience" parameter.');
        }

        return $this->getAll($page, $per_page, ['audience' => $audience]);
    }

    /**
     * Get Client Grants by Client ID.
     *
     * @param string       $client_id Client ID to filter by.
     * @param null|integer $page      The page number, zero based.
     * @param null|integer $per_page  The amount of entries per page.
     *
     * @return mixed
     *
     * @throws CoreException Thrown when $client_id is empty or not a string.
     * @throws \Exception Thrown by the Guzzle HTTP client when there is a problem with the API call.
     */
    public function getByClientId($client_id, $page = null, $per_page = null)
    {
        if (empty($client_id) || ! is_string($client_id)) {
            throw new CoreException('Empty or invalid "client_id" parameter.');
        }

        return $this->getAll($page, $per_page, ['client_id' => $client_id]);
    }

    /**
     * Create a new Client Grant.
     *
     * @param string $client_id Client ID to receive the grant.
     * @param string $audience  Audience identifier for the API being granted.
     * @param array  $scope     Array of scopes for the grant.
     *
     * @return mixed
     *
     * @throws CoreException Thrown when $client_id or $audience are empty or not a string.
     * @throws \Exception Thrown by the Guzzle HTTP client when there is a problem with the API call.
     */
    public function create($client_id, $audience, array $scope = [])
    {
        if (empty($client_id) || ! is_string($client_id)) {
            throw new CoreException('Empty or invalid "client_id" parameter.');
        }

        if (empty($audience) || ! is_string($audience)) {
            throw new CoreException('Empty or invalid "audience" parameter.');
        }

        return $this->apiClient->method('post')
            ->addPath('client-grants')
            ->withBody(json_encode([
                'client_id' => $client_id,
                'audience' => $audience,
                'scope' => $scope,
            ]))
            ->call();
    }

    /**
     * Delete a Client Grant by ID.
     *
     * @param string $id Client Grant ID to delete.
     *
     * @return mixed
     *
     * @throws \Exception Thrown by the Guzzle HTTP client when there is a problem with the API call.
     */
    public function delete($id)
    {
        return $this->apiClient->method('delete')
            ->addPath('client-grants', $id)
            ->call();
    }

    /**
     * Update an existing Client Grant.
     *
     * @param string $id    Client Grant ID to update.
     * @param array  $scope Array of scopes to update; will replace existing scopes, not merge.
     *
     * @return mixed
     *
     * @throws \Exception Thrown by the Guzzle HTTP client when there is a problem with the API call.
     */
    public function update($id, array $scope)
    {
        return $this->apiClient->method('patch')
            ->addPath('client-grants', $id)
            ->withBody(json_encode(['scope' => $scope,]))
            ->call();
    }

    /**
     * Get a Client Grant.
     * TODO: Deprecate, cannot get a Client Grant by ID.
     *
     * @param string      $id       Client Grant ID.
     * @param null|string $audience Client Grant audience to filter by.
     *
     * @return mixed
     *
     * @throws \Exception Thrown by the Guzzle HTTP client when there is a problem with the API call.
     */
    public function get($id, $audience = null)
    {
        $request = $this->apiClient->get()
            ->addPath('client-grants');

        if ($audience !== null) {
            $request = $request->withParam('audience', $audience);
        }

        return $request->call();
    }
}
