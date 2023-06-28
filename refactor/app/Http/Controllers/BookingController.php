<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BookingController
 *
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{

    /**
     * @var BookingRepository
     */
    protected $repository;

    /**
     * BookingController constructor.
     *
     * @param  BookingRepository  $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->repository = $bookingRepository;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $response = [];
            if ($user_id = $request->get('user_id')) {
                $response = $this->repository->getUsersJobs($user_id);

            } elseif ($request->__authenticatedUser->user_type == env('ADMIN_ROLE_ID') || $request->__authenticatedUser->user_type == env('SUPERADMIN_ROLE_ID')) {
                $response = $this->repository->getAll($request);
            }

            return $this->setResponse(['data' => $response]);
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->setResponse([
                'job' => $this->repository->with('translatorJobRel.user')->find($id),
            ]);
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->setResponse([
                'data' => $this->repository->store($request->__authenticatedUser, $request->all()),
            ]);
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }

    /**
     * @param $id
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function update($id, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->setResponse([
                'data' => $this->repository->updateJob($id, array_except($request->all(), ['_token', 'submit']),
                    $request->__authenticatedUser),
            ]);
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function immediateJobEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->setResponse([
                'data' => $this->repository->storeJobEmail($request->all()),
            ]);
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function getHistory(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $response = [];
            if ($user_id = $request->get('user_id')) {
                $response = $this->repository->getUsersJobsHistory($user_id, $request);
            }

            return $this->setResponse(['data' => $response]);
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function acceptJob(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->setResponse([
                'data' => $this->repository->acceptJob($request->all(), $request->__authenticatedUser),
            ]);
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function acceptJobWithId(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->setResponse([
                'data' => $this->repository->acceptJobWithId($request->get('job_id'), $request->__authenticatedUser),
            ]);
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function cancelJob(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->setResponse([
                'data' => $this->repository->cancelJobAjax($request->all(), $request->__authenticatedUser),
            ]);
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function endJob(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->setResponse([
                'data' => $this->repository->endJob($request->all()),
            ]);
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function customerNotCall(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->setResponse([
                'data' => $this->repository->customerNotCall($request->all()),
            ]);
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function getPotentialJobs(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->setResponse([
                'data' => $this->repository->getPotentialJobs($request->__authenticatedUser),
            ]);
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function distanceFeed(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->time || $request->distance) {
                Distance::where('job_id', '=', $request->jobid)->update([
                    'distance' => $request->distance,
                    'time'     => $request->time,
                ]);
            }

            Job::where('id', '=', $request->jobid)->update([
                'admin_comments'   => $request->get('admincomment', ''),
                'flagged'          => $request->flagged === 'true' ? 'yes' : 'no',
                'session_time'     => $request->get('session_time', ''),
                'manually_handled' => $request->manually_handled === 'true' ? 'yes' : 'no',
                'by_admin'         => $request->by_admin === 'true' ? 'yes' : 'no',
            ]);

            return $this->setResponse([], 'Record has been updated.');
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function reopen(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->setResponse([
                'data' => $this->repository->reopen($request->all()),
            ]);
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function resendNotifications(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $job     = $this->repository->find($request->jobid);
            $jobData = $this->repository->jobToData($job);
            $this->repository->sendNotificationTranslator($job, $jobData, '*');

            return $this->setResponse([], 'Push sent');
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Khuram Qadeer.
     */
    public function resendSMSNotifications(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $job = $this->repository->find($request->jobid);
            $this->repository->jobToData($job);
            $this->repository->sendSMSNotificationToTranslator($job);

            return $this->setResponse([], 'SMS sent');
        } catch (\Exception $e) {
            return $this->setResponse([], $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

}
