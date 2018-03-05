<?php
namespace Fasthand\Handler;

/**
 * 订单服务
 * @author 贾仿栋
 * @date 20160324
 */
require_once('util/util.php');
require_once('util/SmsUtil.php');
require_once('config/OrderConfig.php');
require_once('config/MessageCode.php');
require_once('config/FriendConfig.php');
require_once('config/config.php');
require_once('config/autoload.php');

require_once __DIR__ . '/../../MyUtil/util.php';

use Fasthand\Service\Order\OrderServiceIf;
use MyUtil\MyUtil;

class OrderServiceHandler implements OrderServiceIf
{
    /**
     * OrderService constructor.
     */
    public function OrderService()
    {
    }

    /**
     * 记录订单历史
     *
     * @param array $fasthand_order
     */
    public function addOrderHistory(array $fasthand_order)
    {
		$fasthand_order_obj = MyUtil::array2object($fasthand_order, "Fasthand_order");
		var_dump($fasthand_order);
		var_dump($fasthand_order_obj);

        $fasthandOrderHistoryService = new \FasthandOrderHistoryService();
        $fasthand_order_history = new \Fasthand_order_history();
        $fasthand_order_history->setCreate_time(date("Y-m-d H:i:s"));
        $fasthand_order_history->setEvent_id($fasthand_order_obj->getEvent_id());
        $fasthand_order_history->setOrder_id($fasthand_order_obj->getId());
        $fasthand_order_history->setSeller_id($fasthand_order_obj->getSeller_id());
        $fasthand_order_history->setStatus($fasthand_order_obj->getStatus());
        $fasthand_order_history->setType($fasthand_order_obj->getType());
        $fasthand_order_history->setUpdate_time(date("Y-m-d H:i:s"));
        $fasthand_order_history->setUser_id($fasthand_order_obj->getUser_id());
		var_dump($fasthand_order_history);
        //$fasthandOrderHistoryService->add($fasthand_order_history);
    }

    /**
     * 修改支付成功数
     *
     * @param int $eventId
     * @param string $eventType
     * @param int
     */
    public function updatePayNum($eventId, $eventType, $number = 1)
    {
        if ($eventType == ORDER_TYPE_COURSES) {
            $fasthandCoursesService = new \FasthandCoursesService();
            $fasthand_courses = new \Fasthand_courses();
            $fasthand_courses->setId($eventId);
            $fasthandCoursesService->loadByID($fasthand_courses);
            $pay_num = $fasthand_courses->getPay_num();
            $pay_num = $pay_num + $number;
            $fasthand_courses->setPay_num($pay_num);
            $fasthandCoursesService->update($fasthand_courses);
        } elseif ($eventType == ORDER_TYPE_ACTIVITY) {
            $fasthandActivityService = new \FasthandActivityService();
            $fasthand_activity = new \Fasthand_activity();
            $fasthand_activity->setId($eventId);
            $fasthandActivityService->loadByID($fasthand_activity);
            $pay_num = $fasthand_activity->getPay_num();
            $pay_num = $pay_num + $number;
            $fasthand_activity->setPay_num($pay_num);
            $fasthandActivityService->update($fasthand_activity);
        } elseif ($eventType == ORDER_TYPE_TEACHER) {
            $fasthandTeacherService = new \FasthandTeacherService();
            $fasthand_teacher = new \Fasthand_teacher();
            $fasthand_teacher->setId($eventId);
            $fasthandTeacherService->loadByID($fasthand_teacher);
            $pay_num = $fasthand_teacher->getPay_num();
            $pay_num = $pay_num + $number;
            $fasthand_teacher->setPay_num($pay_num);
            $fasthandTeacherService->update($fasthand_teacher);
        }
    }

    /**
     * 消费码已经验证给卖家短信
     *
     * @param int $event_id
     * @param string $type
     * @param int
     */
    public function sendCheckSmsToSeller($event_id, $type, $order_id)
    {
        $mobile = "";
        if (!empty($event_id) && !empty($type)) {
            if ($type == ORDER_TYPE_COURSES) {
                $fasthandCoursesService = new \FasthandCoursesService();
                $fasthand_courses = new \Fasthand_courses();
                $fasthand_courses->setId($event_id);
                $result = $fasthandCoursesService->loadByID($fasthand_courses);
                if ($result) {
                    $userId = $fasthand_courses->getUser_id();
                    if (!empty($userId)) {
                        $fasthandUserService = new \FasthandUserService();
                        $fasthand_user = new \Fasthand_user();
                        $fasthand_user->setId($userId);
                        $result = $fasthandUserService->loadByID($fasthand_user);
                        if ($result) {
                            $mobile = $fasthand_user->getUsername();
                            if (empty($mobile) || !Util::checkMobile($mobile)) {
                                $mobile = $fasthand_user->getMobile();
                            }
                        }
                    }
                }
            } elseif ($type == ORDER_TYPE_ACTIVITY) {
                $fasthandActivityService = new \FasthandActivityService();
                $fasthand_activity = new \Fasthand_activity();
                $fasthand_activity->setId($event_id);
                $result = $fasthandActivityService->loadByID($fasthand_activity);
                if ($result) {
                    $userId = $fasthand_activity->getUser_id();
                    if (!empty($userId)) {
                        $fasthandUserService = new \FasthandUserService();
                        $fasthand_user = new \Fasthand_user();
                        $fasthand_user->setId($userId);
                        $result = $fasthandUserService->loadByID($fasthand_user);
                        if ($result) {
                            $mobile = $fasthand_user->getUsername();
                            if (empty($mobile) || !Util::checkMobile($mobile)) {
                                $mobile = $fasthand_user->getMobile();
                            }
                        }
                    }
                }
            } elseif ($type == ORDER_TYPE_TEACHER) {
                $fasthandTeacherService = new \FasthandTeacherService();
                $fasthand_teacher = new \Fasthand_teacher();
                $fasthand_teacher->setId($event_id);
                $result = $fasthandTeacherService->loadByID($fasthand_teacher);
                if ($result) {
                    $userId = $fasthand_teacher->getUser_id();
                    if (!empty($userId)) {
                        $fasthandUserService = new \FasthandUserService();
                        $fasthand_user = new \Fasthand_user();
                        $fasthand_user->setId($userId);
                        $result = $fasthandUserService->loadByID($fasthand_user);
                        if ($result) {
                            $mobile = $fasthand_user->getUsername();
                            if (empty($mobile) || !Util::checkMobile($mobile)) {
                                $mobile = $fasthand_user->getMobile();
                            }
                        }
                    }
                }
            }
        }
        if (!empty($mobile)) {
            $smsUtil = new \SmsUtil();
            $content = sprintf(MessageCode::$sms_content_order_check_seller, $order_id);
            $sendContent = iconv("UTF-8", "GBK", $content);
            $smsUtil->setDa("86" . $mobile);
            $smsUtil->setSm($sendContent);
            $sendResult = $smsUtil->singleMt();
            $smsUtil->saveSms($sendResult, $content, $mobile);
        }
    }

    /**
     * 消费码已经验证给买家短信
     *
     * @param int $userId
     * @param int $order_id
     * @param int
     */
    public function sendCheckSmsToStudent($userId, $order_id, $integral_num)
    {
        $smsUtil = new \SmsUtil();
        $fasthandUserService = new \FasthandUserService();
        $mobile = "";
        if (!empty($userId)) {
            $fasthand_user = new \Fasthand_user();
            $fasthand_user->setId($userId);
            $result = $fasthandUserService->loadByID($fasthand_user);
            if ($result) {
                $mobile = $fasthand_user->getUsername();
                if (empty($mobile) || !Util::checkMobile($mobile)) {
                    $mobile = $fasthand_user->getMobile();
                }
                $mobile = $fasthand_user->getMobile();
            }
        }
        if (!empty($mobile)) {
            $content = MessageCode::$sms_content_order_check;
            $content = sprintf(MessageCode::$sms_content_order_check, $order_id, $integral_num);
            $sendContent = iconv("UTF-8", "GBK", $content);
            $smsUtil->setDa("86" . $mobile);
            $smsUtil->setSm($sendContent);
            $sendResult = $smsUtil->singleMt();
            $smsUtil->saveSms($sendResult, $content, $mobile);
        }
    }

    /**
     * 给买家发送付款成功通知
     *
     * @param array $fasthand_order
     */
    public function sendPaySmsToStudent(array $fasthand_order)
    {
        $userId = $fasthand_order->getUser_id();
        $consum_code = $fasthand_order->getConsum_code();
        $order_id = $fasthand_order->getId();
        $event_type = $fasthand_order->getType();
        $event_id = $fasthand_order->getEvent_id();
        $smsUtil = new \SmsUtil();
        $fasthandUserService = new \FasthandUserService();
        $mobile = "";
        if (!empty($userId)) {
            $fasthand_user = new \Fasthand_user();
            $fasthand_user->setId($userId);
            $result = $fasthandUserService->loadByID($fasthand_user);
            if ($result) {
                $mobile = $fasthand_user->getUsername();
                if (empty($mobile) || !Util::checkMobile($mobile)) {
                    $mobile = $fasthand_user->getMobile();
                }
            }
        }
        $itemName = "";
        if (!empty($mobile)) {

            switch ($event_type) {
                case ORDER_TYPE_ACTIVITY:
                    $fasthandItemBean = $this->getItemByActivity($event_id, $userId);
                    $itemName = $fasthandItemBean->getName();
                    break;
                case ORDER_TYPE_TEACHER:
                    $fasthandItemBean = $this->getItemByTeacher($event_id);
                    $itemName = $fasthandItemBean->getName();
                    break;
                case ORDER_TYPE_COURSES:
                    $fasthandItemBean = $this->getItemByCourses($event_id, $userId);
                    $itemName = $fasthandItemBean->getName();
                    break;
                default:
            }
        }
        $sendContent = sprintf(MessageCode::$sms_content_order_pay, $itemName, $consum_code);
        $dbContent = $sendContent;
        $sendContent = iconv("UTF-8", "GBK", $sendContent);
        $smsUtil->setDa("86" . $mobile);
        $smsUtil->setSm($sendContent);
        $sendResult = $smsUtil->singleMt();
        $smsUtil->saveSms($sendResult, $dbContent, $mobile);
    }

    /**
     * 给卖家发短信&&添加买家和卖家的自动好友关系
     *
     * @param array $fasthand_order
     * @param int $friend_user_id
     */
    public function sendPaySmsToSeller(array $fasthand_order, $friend_user_id = "")
    {
        $mobile = "";
        $event_id = $fasthand_order->getEvent_id();
        $order_id = $fasthand_order->getId();
        $type = $fasthand_order->getType();
        if (!empty($event_id) && !empty($type)) {
            if ($type == ORDER_TYPE_COURSES) {
                $fasthandCoursesService = new \FasthandCoursesService();
                $fasthand_courses = new \Fasthand_courses();
                $fasthand_courses->setId($event_id);
                $result = $fasthandCoursesService->loadByID($fasthand_courses);
                if ($result) {
                    $userId = $fasthand_courses->getUser_id();
                    if (!empty($userId)) {
                        $fasthandUserService = new \FasthandUserService();
                        $fasthand_user = new \Fasthand_user();
                        $fasthand_user->setId($userId);
                        $result = $fasthandUserService->loadByID($fasthand_user);
                        if ($result) {
                            $mobile = $fasthand_user->getUsername();
                            if (empty($mobile) || !Util::checkMobile($mobile)) {
                                $mobile = $fasthand_user->getMobile();
                            }
                        }
                    }
                }
            } elseif ($type == ORDER_TYPE_ACTIVITY) {
                $fasthandActivityService = new \FasthandActivityService();
                $fasthand_activity = new \Fasthand_activity();
                $fasthand_activity->setId($event_id);
                $result = $fasthandActivityService->loadByID($fasthand_activity);
                if ($result) {
                    $userId = $fasthand_activity->getUser_id();
                    if (!empty($userId)) {
                        $fasthandUserService = new \FasthandUserService();
                        $fasthand_user = new \Fasthand_user();
                        $fasthand_user->setId($userId);
                        $result = $fasthandUserService->loadByID($fasthand_user);
                        if ($result) {
                            $mobile = $fasthand_user->getUsername();
                            if (empty($mobile) || !Util::checkMobile($mobile)) {
                                $mobile = $fasthand_user->getMobile();
                            }
                        }
                    }
                }
            } elseif ($type == ORDER_TYPE_TEACHER) {
                $fasthandTeacherService = new \FasthandTeacherService();
                $fasthand_teacher = new \Fasthand_teacher();
                $fasthand_teacher->setId($event_id);
                $result = $fasthandTeacherService->loadByID($fasthand_teacher);
                if ($result) {
                    $userId = $fasthand_teacher->getUser_id();
                    if (!empty($userId)) {
                        $fasthandUserService = new \FasthandUserService();
                        $fasthand_user = new \Fasthand_user();
                        $fasthand_user->setId($userId);
                        $result = $fasthandUserService->loadByID($fasthand_user);
                        if ($result) {
                            $mobile = $fasthand_user->getUsername();
                            if (empty($mobile) || !Util::checkMobile($mobile)) {
                                $mobile = $fasthand_user->getMobile();
                            }
                        }
                    }
                }

            }
        }
        if (!empty($mobile)) {
// 			if(RELEASE_STATUS != "dev"){
            $smsUtil = new \SmsUtil();
            $sendContent = sprintf(MessageCode::$sms_content_order_pay_seller, $order_id);
            $dbContent = $sendContent;
            $sendContent = iconv("UTF-8", "GBK", $sendContent);
            $smsUtil->setDa("86" . $mobile);
            $smsUtil->setSm($sendContent);
            $sendResult = $smsUtil->singleMt();
            $smsUtil->saveSms($sendResult, $dbContent, $mobile);
// 			}
        }
        if (!empty($friend_user_id) && !empty($userId)) {
            $friendLogicService = new \FriendLogicService();
            $friendLogicService->addFriendRequest($userId, $friend_user_id, "", RELEATION_STATUS_FRIEND);
        }

    }

    /**
     * 修改优惠使用状态
     *
     * @param int $id
     * @param int $status
     */
    public function updatePromotionStatus($id, $status)
    {
        $fasthandMyPromotionService = new \FasthandMyPromotionService();
        $fasthand_my_promotion = new \Fasthand_my_promotion();
        $fasthand_my_promotion->setId($id);
        $result = $fasthandMyPromotionService->loadByID($fasthand_my_promotion);
        if ($result) {
            $fasthand_my_promotion->setStatus($status);
            $fasthand_my_promotion->setUpdate_time(date("Y-m-d H:i:s"));
            $fasthandMyPromotionService->update($fasthand_my_promotion);
            $promotionId = $fasthand_my_promotion->getPromotion_id();
            $this->mUpdatePromotionUseNum($promotionId, $status);
        }
    }

    /**
     * 修改优惠使用数量
     *
     * @param int $promotionId
     * @param int $status
     */
    public function mUpdatePromotionUseNum($promotionId, $status)
    {
        $fasthandItemPromotionService = new \FasthandItemPromotionService();
        $fasthand_item_promotion = new \Fasthand_item_promotion();
        $fasthand_item_promotion->setId($promotionId);
        $fasthandItemPromotionService->loadByID($fasthand_item_promotion);
        $use_num = $fasthand_item_promotion->getUse_num();
        if ($status == ORDER_MY_PROMOTION_STATUS_GET) {
            $use_num = $use_num - 1;
        } else {
            $use_num = $use_num + 1;
        }
        if ($use_num < 0) {
            $use_num = "0";
        }
        $fasthand_item_promotion->setUse_num($use_num);
        $fasthandItemPromotionService->update($fasthand_item_promotion);
    }

    /**
     * 发送未支付的短信提醒
     *
     * @param int $userId
     */
    public function sendNotPaySms($userId)
    {
        $smsUtil = new \SmsUtil();
        $fasthandUserService = new \FasthandUserService();
        $mobile = "";
        if (!empty($userId)) {
            $fasthand_user = new \Fasthand_user();
            $fasthand_user->setId($userId);
            $result = $fasthandUserService->loadByID($fasthand_user);
            if ($result) {
                $mobile = $fasthand_user->getUsername();
            }
        }
        if (!empty($mobile)) {
            $sendContent = \MessageCode::$sms_content_order_notpay;
            $dbContent = $sendContent;
            $sendContent = iconv("UTF-8", "GBK", $sendContent);
            $smsUtil->setDa("86" . $mobile);
            $smsUtil->setSm($sendContent);
			var_dump($smsUtil);
            $sendResult = $smsUtil->singleMt();
            $smsUtil->saveSms($sendResult, $dbContent, $mobile);
        }
    }

    /**
     * 发送未支付的消息提醒
     *
     * @param int $user_id
     * @param int $event_id
     * @param string $type
     */
    public function sendNotPayMessage($user_id, $event_id, $type)
    {
        $role = MessageCode::$message_role_all;
        $invalidTime = "2080-01-01 00:00:00";
        $dataArray = array();
        $dataParamArray = array();
        $dataArray["type"] = "orderList";
        $this->addMessage("0", "", MessageCode::$message_content_notpay, date("Y-m-d H:i:s"), $invalidTime, MessageCode::$message_type_personal,
            MessageCode::$message_title_notpay, $user_id, $role, $dataArray);
    }

    /**
     * 判断手机号码是否允许领取优惠券, return 1,允许；2，已经下线；3，已经领用
     *
     * @param string $mobile
     * @param int $promotion_id
     * @param array $fasthand_item_promotion
     * @param int $user_id
     * @param float $amount
     * @return string
     */
    public function checkAllowReceiveCouponByMobile($mobile, $promotion_id, array $fasthand_item_promotion, $user_id, $amount)
    {
        //通过手机号码获取用户编号
        $fasthandItemPromotionService = new \FasthandItemPromotionService();
        $fasthandMyPromotionService = new \FasthandMyPromotionService();
        $fasthandUserService = new \FasthandUserService();
        $fasthand_userQuery = new \Fasthand_userQuery();
        $fasthand_userQuery->setUsername($mobile);
        $fasthand_userArray = $fasthandUserService->listByquery($fasthand_userQuery);
        if (!empty($fasthand_userArray) && count($fasthand_userArray) > 0) {
            $fasthand_user = $fasthand_userArray[0];
            $user_id = $fasthand_user->getId();
        }

        $fasthand_item_promotion = new \Fasthand_item_promotion();
        $fasthand_item_promotion->setId($promotion_id);
        $fasthandItemPromotionService->loadByID($fasthand_item_promotion);
        $user_receive_num = $fasthand_item_promotion->getUser_receive_num();
        $promotion_num = $fasthand_item_promotion->getPromotion_num();
        $status = $fasthand_item_promotion->getStatus();
        $receive_num = $fasthand_item_promotion->getReceive_num();
        $again_receive = $fasthand_item_promotion->getAgain_receive();
        if (empty($promotion_num)) {
            $promotion_num = "9999999999";
        }
        if (empty($user_receive_num)) {
            $user_receive_num = 0;
        }
        //获取用户领用次数
        $fasthand_my_promotionQuery = new \Fasthand_my_promotionQuery();
        if (!empty($user_id)) {
            $fasthand_my_promotionQuery->setUser_id($user_id);
        }
        if (!empty($mobile)) {
            $fasthand_my_promotionQuery->setMobile($mobile);
        }
        if ($again_receive == "1") {
            //不允许重复使用
            $fasthand_my_promotionQuery->setStatus("1");
        }
        $fasthand_my_promotionQuery->setPromotion_id($promotion_id);
        $fasthand_my_promotionArray = $fasthandMyPromotionService->listByquery($fasthand_my_promotionQuery);
        $total = count($fasthand_my_promotionArray);
        if ($total < $user_receive_num) {
            if ($receive_num >= $promotion_num or $status == "0") {
                //已经下线或已经用完
                if (!empty($fasthand_my_promotionArray)) {
                    $fasthand_my_promotion = $fasthand_my_promotionArray[0];
                    $amount = $fasthand_my_promotion->getAmount();
                    return "3";
                }
                return "2";
            } else {

                return "1";
            }
        } else {

            if (!empty($fasthand_my_promotionArray)) {
                $fasthand_my_promotion = $fasthand_my_promotionArray[0];
                $amount = $fasthand_my_promotion->getAmount();
                return "3";
            }
        }
        return "2";
    }

    /**
     * 判断用户编号是否允许领取优惠券, return 1,允许；2，已经下线；3，已经领用
     *
     * @param int $userId
     * @param int $promotion_id
     * @param float $amount
     * @return string
     */
    public function checkAllowReceiveCouponByUserId($userId, $promotion_id, $amount)
    {
        $fasthandItemPromotionService = new \FasthandItemPromotionService();
        $fasthandMyPromotionService = new \FasthandMyPromotionService();
        $fasthand_item_promotion = new \Fasthand_item_promotion();
        $fasthand_item_promotion->setId($promotion_id);
        $fasthandItemPromotionService->loadByID($fasthand_item_promotion);
        $user_receive_num = $fasthand_item_promotion->getUser_receive_num();
        $promotion_num = $fasthand_item_promotion->getPromotion_num();
        $status = $fasthand_item_promotion->getStatus();
        $receive_num = $fasthand_item_promotion->getReceive_num();
        $again_receive = $fasthand_item_promotion->getAgain_receive();
        if (empty($promotion_num)) {
            $promotion_num = "9999999999";
        }
        if (empty($user_receive_num)) {
            $user_receive_num = 0;
        }
        //获取用户领用次数
        $fasthand_my_promotionQuery = new \Fasthand_my_promotionQuery();
        if (!empty($userId)) {
            $fasthand_my_promotionQuery->setUser_id($userId);
        }

        if ($again_receive == "1") {
            //不允许重复使用
            $fasthand_my_promotionQuery->setStatus("1");
        }
        $fasthand_my_promotionQuery->setPromotion_id($promotion_id);
        $fasthand_my_promotionArray = $fasthandMyPromotionService->listByquery($fasthand_my_promotionQuery);
        $total = count($fasthand_my_promotionArray);
        if ($total < $user_receive_num) {
            if ($receive_num >= $promotion_num or $status == "0") {
                //已经下线或已经用完
                if (!empty($fasthand_my_promotionArray)) {
                    $fasthand_my_promotion = $fasthand_my_promotionArray[0];
                    $amount = $fasthand_my_promotion->getAmount();
                    return "3";
                }
                return "2";
            } else {

                return "1";
            }
        } else {
            if (!empty($fasthand_my_promotionArray)) {
                $fasthand_my_promotion = $fasthand_my_promotionArray[0];
                $amount = $fasthand_my_promotion->getAmount();
                return "3";
            }
        }
        return "2";
    }

    /**
     * 用户注册后修改
     *
     * @param string $mobile
     * @param int $userId
     * @return bool
     */
    public function updatePromotionUserByRegister($mobile, $userId)
    {
        if (!empty($userId) && !empty($mobile)) {
            $fasthandMyPromotionService = new \FasthandMyPromotionService();
            $fasthand_my_promotionQuery = new \Fasthand_my_promotionQuery();
            $fasthand_my_promotionQuery->setMobile($mobile);
            $fasthandMyPromotionService->updateUidByQuery($userId, $fasthand_my_promotionQuery);
            return true;
        }
        return false;
    }

    /**
     * 判断优惠券状态
     *
     * @param int $my_promotion_id
     * @param int $userId
     * @return bool
     */
    public function checkPromotionStatus($my_promotion_id, $userId)
    {
        if (!$my_promotion_id || !$userId) return false;
        $fasthandMyPromotionService = new \FasthandMyPromotionService();
        $fasthand_my_promotionQuery = new \Fasthand_my_promotionQuery();
        $fasthand_my_promotionQuery->setId($my_promotion_id);
        $fasthand_my_promotionQuery->setStatus("1");
        $fasthand_my_promotionQuery->setUser_id($userId);
        $total = $fasthandMyPromotionService->countByQuery($fasthand_my_promotionQuery);
        return $total > 0 ? true : false;
    }

    /**
     * 判断商品库存是否够
     *
     * @param int $event_id
     * @param string $type
     * @param int $number
     * @param array $itemOriginVo
     * @return bool
     */
    public function checkInventoryStatus($event_id, $type, $number, array $itemOriginVo = null)
    {
        if (!empty($itemOriginVo)) {
            $inventory_num = $itemOriginVo->getInventory_num();
        } else {
            if ($type == ORDER_TYPE_TEACHER) {
                $object = BaseLogicService::getPublicTeacherInfoById($event_id);
            } else if ($type == ORDER_TYPE_COURSES) {
                $object = BaseLogicService::getPublicCoursesInfoById($event_id);
            } else if ($type == ORDER_TYPE_ACTIVITY) {
                $object = BaseLogicService::getPublicActivityInfoById($event_id);
            }
            $inventory_num = $object ? $object->getInventory_num() : 0;
        }
        if ($inventory_num < $number) {
            return false;
        }
        return true;
    }

    /**
     * 获取sku的库存量
     *
     * @param int $event_id
     * @param string $event_type
     * @param int $sku_id
     * @param int $number
     * @return bool
     */
    public function checkSkuInventoryStatus($event_id, $event_type, $sku_id, $number)
    {
        $inventory_num = 0;
        $fasthandItemSkuService = new \FasthandItemSkuService();
        $fasthand_item_sku = new \Fasthand_item_sku();
        $fasthand_item_sku->setId($sku_id);
        $fasthandItemSkuService->loadByID($fasthand_item_sku);
        $inventory_num = $fasthand_item_sku->getInventory_num();
        if ($inventory_num < $number) {
            return false;
        }
        return true;
    }

    /**
     * 修改商品库存数
     *
     * @param array $fasthand_order
     * @param string $type
     * @return bool
     */
    public function updateInventoryNumber(array $fasthand_order, $type = "1")
    {
        $event_id = "";
        $event_type = "";
        $number = "";
        $sku_id = "";
        $consum_code = "";
        $status = "0";
        if (!empty($fasthand_order)) {
            $event_id = $fasthand_order->getEvent_id();
            $event_type = $fasthand_order->getType();
            $number = $fasthand_order->getNumber();
            $sku_id = $fasthand_order->getSku_id();
            $consum_code = $fasthand_order->getConsum_code();
            $status = $fasthand_order->getStatus();

        }
        if ($type == "2" && !empty($consum_code)) {
            //修改消费码状态
            $fasthandOrderConsumcodeService = new \FasthandOrderConsumcodeService();
            $fasthand_order_consumcodeQuery = new \Fasthand_order_consumcodeQuery();
            $fasthand_order_consumcodeQuery->setEvent_id($event_id);
            $fasthand_order_consumcodeQuery->setEvent_type($event_type);
            $fasthand_order_consumcodeQuery->setConsum_code($consum_code);
            $fasthand_order_consumcodeArray = $fasthandOrderConsumcodeService->listByqueryTopage($fasthand_order_consumcodeQuery, 0, 1);
            if (!empty($fasthand_order_consumcodeArray) && count($fasthand_order_consumcodeArray) > 0) {
                $fasthand_order_consumcode = $fasthand_order_consumcodeArray[0];
                $fasthand_order_consumcode->setStatus("1");
                $fasthand_order_consumcode->setUpdate_time(date("Y-m-d H:i:s"));
                $fasthandOrderConsumcodeService->update($fasthand_order_consumcode);
                if ($status == ORDER_STATUS_INVALID || $status == ORDER_STATUS_CLOSE) {
                    //注销掉订单的消费码
                    $fasthandOrderService = new \FasthandOrderService();
                    $fasthand_order->setConsum_code("");
                    $fasthand_order->setUpdate_time(date("Y-m-d H:i:s"));
                    $fasthand_order->setOut_trade_no("");
                    $fasthandOrderService->update($fasthand_order);
                }

            }
        }
        if ($event_type == ORDER_TYPE_TEACHER) {
            $fasthandTeacherService = new \FasthandTeacherService();
            $fasthand_teacher = new \Fasthand_teacher();
            $fasthand_teacher->setId($event_id);
            $fasthandTeacherService->loadByID($fasthand_teacher);
            $inventory_num = $fasthand_teacher->getInventory_num();
            if ($type == "1") {
                $inventory_num = $inventory_num - $number;
            } else {
                $inventory_num = $inventory_num + $number;
            }
            if ($inventory_num < 0) {
                $inventory_num = 0;
            }
            $fasthand_teacher->setInventory_num($inventory_num);
            $fasthandTeacherService->update($fasthand_teacher);
        } elseif ($event_type == ORDER_TYPE_COURSES) {
            $fasthandCoursesService = new \FasthandCoursesService();
            $fasthand_courses = new \Fasthand_courses();
            $fasthand_courses->setId($event_id);
            $fasthandCoursesService->loadByID($fasthand_courses);
            $inventory_num = $fasthand_courses->getInventory_num();
            if ($type == "1") {
                $inventory_num = $inventory_num - $number;
            } else {
                $inventory_num = $inventory_num + $number;
            }
            if ($inventory_num < 0) {
                $inventory_num = 0;
            }
            $fasthand_courses->setInventory_num($inventory_num);
            $fasthandCoursesService->update($fasthand_courses);
        } elseif ($event_type == ORDER_TYPE_ACTIVITY) {
            $fasthandActivityService = new \FasthandActivityService();
            $fasthand_activity = new \Fasthand_activity();
            $fasthand_activity->setId($event_id);
            $fasthandActivityService->loadByID($fasthand_activity);
            $inventory_num = $fasthand_activity->getInventory_num();
            if ($type == "1") {
                $inventory_num = $inventory_num - $number;
            } else {
                $inventory_num = $inventory_num + $number;
            }
            if ($inventory_num < 0) {
                $inventory_num = 0;
            }
            $fasthand_activity->setInventory_num($inventory_num);
            $fasthandActivityService->update($fasthand_activity);
        }
        if (!empty($sku_id)) {
            //变更sku的库存量
            $fasthandItemSkuService = new \FasthandItemSkuService();
            $fasthand_item_sku = new \Fasthand_item_sku();
            $fasthand_item_sku->setId($sku_id);
            $fasthandItemSkuService->loadByID($fasthand_item_sku);
            $sku_inventory_num = $fasthand_item_sku->getInventory_num();
            if ($type == "1") {
                $sku_inventory_num = $sku_inventory_num - $number;
            } else {
                $sku_inventory_num = $sku_inventory_num + $number;
            }
            $fasthand_item_sku->setInventory_num($sku_inventory_num);
            $fasthandItemSkuService->update($fasthand_item_sku);
        }
        return true;
    }

    /**
     * 同步各个商品库的优惠状态
     *
     * @param int  $event_id
     * @param string $event_type
     * @param int
     */
    public function syncPromotionToItem($event_id, $event_type, $type = "1")
    {
        $event_idArray = split(",", $event_id);
        $deletePromotionEventIds = "";//删除商品上的优惠标记
        $addPromotionEventIds = "";//添加商品上的优惠标记
        $fasthandItemPromotionService = new \FasthandItemPromotionService();
        $fasthandCoursesService = new \FasthandCoursesService();
        $fasthandTeacherService = new \FasthandTeacherService();
        $fasthandActivityService = new \FasthandActivityService();
        if ($type == "2") {
            //删除优惠处理
            foreach ($event_idArray as $event_id) {
                if (!empty($event_id)) {
                    $fasthand_item_promotionQuery = new \Fasthand_item_promotionQuery();
                    $fasthand_item_promotionQuery->setOnlyEventId("," . $event_id . ",");
                    $fasthand_item_promotionQuery->setStatus(PROMOTION_STATUS_ONLINE);
                    $fasthand_item_promotionQuery->setItem_type($event_type);
                    $total = $fasthandItemPromotionService->countByQuery($fasthand_item_promotionQuery);
                    if ($total <= 0) {
                        $deletePromotionEventIds .= $event_id . ",";
                    }
                }
            }
        } else {
            //添加优惠
            foreach ($event_idArray as $event_id) {
                if (!empty($event_id)) {
                    $addPromotionEventIds .= $event_id . ",";
                }
            }
        }
        if (!empty($addPromotionEventIds)) {
            $addPromotionEventIds = substr($addPromotionEventIds, 0, strlen($addPromotionEventIds) - 1);
        }
        if (!empty($deletePromotionEventIds)) {
            $deletePromotionEventIds = substr($deletePromotionEventIds, 0, strlen($deletePromotionEventIds) - 1);
        }
        if ($event_type == ORDER_TYPE_TEACHER) {
            if (!empty($addPromotionEventIds)) {
                $fasthand_teacherQuery = new \Fasthand_teacherQuery();
                $fasthand_teacherQuery->setIds($addPromotionEventIds);
                $fasthandTeacherService->addPromotionByQuery($fasthand_teacherQuery);
            }
            if (!empty($deletePromotionEventIds)) {
                $fasthand_teacherQuery = new \Fasthand_teacherQuery();
                $fasthand_teacherQuery->setIds($deletePromotionEventIds);
                $fasthandTeacherService->deletePromotionByQuery($fasthand_teacherQuery);
            }
        } elseif ($event_type == ORDER_TYPE_ACTIVITY) {
            if (!empty($addPromotionEventIds)) {
                $fasthand_activityQuery = new \Fasthand_activityQuery();
                $fasthand_activityQuery->setIds($addPromotionEventIds);
                $fasthandActivityService->addPromotionByQuery($fasthand_activityQuery);
            }
            if (!empty($deletePromotionEventIds)) {
                $fasthand_activityQuery = new \Fasthand_activityQuery();
                $fasthand_activityQuery->setIds($deletePromotionEventIds);
                $fasthandActivityService->deletePromotionByQuery($fasthand_activityQuery);
            }
        } elseif ($event_type == ORDER_TYPE_COURSES) {
            if (!empty($addPromotionEventIds)) {
                $fasthand_coursesQuery = new \Fasthand_coursesQuery();
                $fasthand_coursesQuery->setIds($addPromotionEventIds);
                $fasthandCoursesService->addPromotionByQuery($fasthand_coursesQuery);
            }
            if (!empty($deletePromotionEventIds)) {
                $fasthand_coursesQuery = new \Fasthand_coursesQuery();
                $fasthand_coursesQuery->setIds($deletePromotionEventIds);
                $fasthandCoursesService->deletePromotionByQuery($fasthand_coursesQuery);
            }
        }
    }

    /**
     * 获取发布优惠的用户编号
     *
     * @param string $event_type
     * @param int $event_id
     * @return int
     */
    public function getPromotionUserId($event_type, $event_id)
    {
        if ($event_type == ORDER_TYPE_COURSES) {
            $fasthandCoursesService = new \FasthandCoursesService();
            $fasthand_courses = new \Fasthand_courses();
            $fasthand_courses->setId($event_id);
            $fasthandCoursesService->loadByID($fasthand_courses);
            $userId = $fasthand_courses->getUser_id();
        } elseif ($event_type == ORDER_TYPE_ACTIVITY) {
            $fasthandActivityService = new \FasthandActivityService();
            $fasthand_activity = new \Fasthand_activity();
            $fasthand_activity->setId($event_id);
            $fasthandActivityService->loadByID($fasthand_activity);
            $userId = $fasthand_activity->getUser_id();
        } elseif ($event_type == ORDER_TYPE_TEACHER) {
            $fasthandTeacherService = new \FasthandTeacherService();
            $fasthand_teacher = new \Fasthand_teacher();
            $fasthand_teacher->setId($event_id);
            $fasthandTeacherService->loadByID($fasthand_teacher);
            $userId = $fasthand_teacher->getUser_id();
        }
        return $userId;
    }

    /**
     * 新增消息
     *
     * @param int $city_id //指定城市编号，0代表不限
     * @param string $client_source //所对应平台，空代表不想
     * @param string $content //消息内容
     * @param string $effective_time //生效时间
     * @param string $invalid_time //失效时间
     * @param int $message_type //消息类型
     * @param string $title //标题
     * @param int $userId //用户编号
     * @param int $userRole //用户角色
     * @param array $dataArray
     * @return bool
     */
    public function addMessage($city_id, $client_source, $content, $effective_time, $invalid_time, $message_type, $title, $userId, $userRole, array $dataArray)
    {
        $fasthandMessageService = new \FasthandMessageService();
        $fasthand_message = new \Fasthand_message();
        $dataJson = urlencode(json_encode($dataArray));
        $fasthand_message->setCity_id($city_id);
        $fasthand_message->setClient_source($client_source);
        $fasthand_message->setContent($content);
        $fasthand_message->setCreate_time(date("Y-m-d H:i:s"));
        $fasthand_message->setEffective_time($effective_time);
        $fasthand_message->setInvalid_time($invalid_time);
        $fasthand_message->setMessage_type($message_type);
        $fasthand_message->setSend_status(MessageCode::$message_status_new);
        $fasthand_message->setTitle($title);
        $fasthand_message->setUser_id($userId);
        $fasthand_message->setUser_role($userRole);
        $fasthand_message->setData($dataJson);
        $result = $fasthandMessageService->add($fasthand_message);
        return $result;
    }

    /**
     * 通过商品信息获取优惠信息
     *
     * @param int $event_id
     * @param string $event_type
     * @return array
     */
    public function getPomotionInfoByItem($event_id, $event_type)
    {
        $fasthandItemPromotionService = new \FasthandItemPromotionService();
        $fasthand_item_promotionQuery = new \Fasthand_item_promotionQuery();
        $fasthand_item_promotionQuery->setItem_type($event_type);
        $fasthand_item_promotionQuery->setOnlyEventId("," . $event_id . ",");
        $fasthand_item_promotionQuery->setStatus(PROMOTION_STATUS_ONLINE);
        $fasthand_item_promotionArray = $fasthandItemPromotionService->listByquery($fasthand_item_promotionQuery);
        $currentAmount = "0";
        $returnId = 0;
        for ($i = 0; $i < count($fasthand_item_promotionArray); $i++) {
            $fasthand_item_promotion = $fasthand_item_promotionArray[$i];
            $amount = $fasthand_item_promotion->getAmount();
            if ($currentAmount < $amount) {
                $currentAmount = $amount;
                $returnId = $i;
            }
        }
        if (!empty($fasthand_item_promotionArray) && count($fasthand_item_promotionArray) > 0) {
            return $fasthand_item_promotionArray[$returnId];
        }
        return array();
    }

    /**
     * 获取商品sku信息
     *
     * @param int $event_id
     * @param string $event_type
     * @param array $extendInfoArray
     * @return array
     */
    public function getItemSkuList($event_id, $event_type, array $extendInfoArray = array())
    {
        $pidNameArray = array();
        $vidNameArray = array();
        if (empty($event_id) || empty($event_type)) {
            return array();
        }
        if (!empty($extendInfoArray)) {
            $fasthand_item_propArray = $extendInfoArray['prop'];
            $fasthandItemPropValueBeanArray = $extendInfoArray['propValue'];
            foreach ($fasthand_item_propArray as $fasthand_item_prop) {
                $pid = $fasthand_item_prop->getId();
                $pname = $fasthand_item_prop->getName();
                $pidNameArray[$pid] = $pname;
            }
            foreach ($fasthandItemPropValueBeanArray as $fasthandItemPropValueBean) {
                $vid = $fasthandItemPropValueBean->getId();
                $vname = $fasthandItemPropValueBean->getName();
                $vidNameArray[$vid] = $vname;
            }
        }
        $fasthandItemSkuBeanArray = array();
        $fasthandItemSkuService = new \FasthandItemSkuService();
        $fasthand_item_skuQuery = new \Fasthand_item_skuQuery();
        $fasthand_item_skuQuery->setEvent_type($event_type);
        $fasthand_item_skuQuery->setEvent_id($event_id);
        $fasthand_item_skuArray = $fasthandItemSkuService->listByquery($fasthand_item_skuQuery);
        if (!empty($fasthand_item_skuArray) && count($fasthand_item_skuArray) > 0) {
            foreach ($fasthand_item_skuArray as $fasthand_item_sku) {
                $fasthandItemSkuBean = new \FasthandItemSkuBean();
                $fasthandItemSkuBean->copyProperties($fasthand_item_sku);
                $properties = $fasthandItemSkuBean->getProperties();
                $propArray = split(ITEM_PROP_SEPARATOR, $properties);
                $properties_name = "";
                $properties_show_name = "";
                foreach ($propArray as $prop) {
                    $propItemArray = split(ITEM_PROP_VALUE_SEPARATOR, $prop);
                    list($pid, $vid) = $propItemArray;
                    $pname = $pidNameArray[$pid];
                    $vname = $vidNameArray[$vid];
                    if (empty($pid) || empty($vid) || empty($pname) || empty($vname)) {
                        continue;
                    }
                    $properties_name .= $pid . ":" . $vid . ":" . $pname . ":" . $vname . ";";
                    $properties_show_name .= $pname . ":" . $vname . ",";
                }
                if (!empty($properties_name)) {
                    $properties_name = substr($properties_name, 0, -1);
                    $properties_show_name = substr($properties_show_name, 0, -1);
                    $fasthandItemSkuBean->setProperties_name($properties_name);
                    $fasthandItemSkuBean->setProperties_show_name($properties_show_name);
                }
                $fasthandItemSkuBeanArray[] = $fasthandItemSkuBean;
            }
        }
        return $fasthandItemSkuBeanArray;
    }

    /**
     * 通过活动添加sku信息
     *
     * @param array $fasthand_activity
     * @return array 
     */
    public function addSkuInfoByActivity(array $fasthand_activity)
    {
        global $fasthand_activity_package_type_array;
        $fasthand_item_sku = new \Fasthand_item_sku();
        $package_type = $fasthand_activity->getPackage_type();
        if (!empty($package_type)) {
            $size_num = $fasthand_activity_package_type_array[$package_type]['size'];
        }
        if (empty($size_num)) {
            $size_num = "1";
        }
        $fasthand_item_sku->setEvent_id($fasthand_activity->getId());
        $fasthand_item_sku->setEvent_type(ORDER_TYPE_ACTIVITY);
        $fasthand_item_sku->setInventory_num($fasthand_activity->getInventory_num());
        $fasthand_item_sku->setPrice($fasthand_activity->getPackage_price());
        $fasthand_item_sku->setPackage_type($fasthand_activity->getPackage_type());
        $fasthand_item_sku->setSize_num($size_num);
        $fasthandItemSkuBean = new \FasthandItemSkuBean();
        $fasthandItemSkuBean->copyProperties($fasthand_item_sku);
        return $fasthandItemSkuBean;
    }

    /**
     * 获取支付可抵扣的积分
     *
     * @param int $userId
     * @return int
     */
    public function getOrderIntegralNum($userId)
    {
        $fasthand_user = new \Fasthand_user();
        $fasthandUserService = new \FasthandUserService();
        $fasthand_user->setId($userId);
        $fasthandUserService->loadByID($fasthand_user);
        return $fasthand_user->getIntegral_num();
    }

    /**
     * 获取当前积分可能递减
     *
     * @param int $userId
     * @param float $item_price
     * @param int $integral_num
     * @return float
     */
    public function getOrderIntegralConvertPrice($userId, $item_price, $integral_num)
    {
        $return_integral_num = 0;
        if (!empty($item_price)) {
            $fasthand_user = new \Fasthand_user();
            $fasthandUserService = new \FasthandUserService();
            $fasthand_user->setId($userId);
            $fasthandUserService->loadByID($fasthand_user);
            $current_integral_num = $fasthand_user->getIntegral_num();
            $use_integral_num = $item_price * 100;
            if ($current_integral_num > $use_integral_num) {
                //判断当前用户所拥有积分是否大于当前购买商品所能使用积分
                $return_integral_num = $use_integral_num;
            } else {
                $return_integral_num = $current_integral_num;
            }
            if ($return_integral_num > $integral_num) {
                //判断客户端传递的使用积分额度是否大于当前可使用积分
                $return_integral_num = $integral_num;
            }
            $return_price = $return_integral_num / 100;
            return $return_price;
        }
        return 0;
    }

    /**
     * 将下单的身份证认证信息转码为订单的留言信息
     *
     * @param string $certificates
     * @return string
     */
    public function transcodeCertificatesToDescribes($certificates)
    {
        $certificateArray = json_decode($certificates, true);
        $describes = "";
        if (!empty($certificateArray) && count($certificateArray) > 0) {
            $describes = "身份证信息：\n\r";
        }
        foreach ($certificateArray as $certificate) {
            $name = $certificate['name'];
            $number = $certificate['number'];
            $describes .= $name . ":" . $number . "\n\r";
        }
        if (!empty($describes)) {
            $describes = substr($describes, 0, strlen($describes) - 2);
        }
        return $describes;
    }

    /**
     * 获取优惠价格
     *
     * @param int $type
     * @param float $price
     * @param float $amount
     * @return float
     */
    public function getPromotionPrice($type, $price, $amount)
    {
        global $fasthand_promotion_discount_array;
        $promotionPrice = 0;
        if (in_array($type, $fasthand_promotion_discount_array)) {
            //折扣券判断
            $promotionPrice = $price * ((100 - $amount) / 100);
        } else {
            //其他优惠
            $promotionPrice = $amount;
        }
        return $promotionPrice;
    }

    /**
     * 通过优惠价和优惠比例获取原价
     *
     * @param int $type
     * @param float $promotionPrice
     * @param float $amount
     * @return float
     */
    public function getPriceByDiscountRate($type, $promotionPrice, $amount)
    {
        global $fasthand_promotion_discount_array;
        $price = 0;
        if (in_array($type, $fasthand_promotion_discount_array)) {
            //折扣券判断
            $price = $promotionPrice / ((100 - $amount) / 100);
        } else {
            //其他优惠
            $price = $promotionPrice + $amount;
        }
        return $price;
    }

    /**
     * 通过sku属性获取pids和vids
     *
     * @param string $props
     * @param string $pids
     * @param string $vids
     */
    public function getPropValueByProps($props, $pids, $vids)
    {
        $propArray = split(ITEM_PROP_SEPARATOR, $props);
        $pidArray = array();
        $vidArray = array();
        foreach ($propArray as $prop) {
            $propItemArray = split(ITEM_PROP_VALUE_SEPARATOR, $prop);
            $pid = $propItemArray[0];
            $vid = $propItemArray[1];
            array_push($pidArray, $pid);
            array_push($vidArray, $vid);
        }
        foreach ($pidArray as $pid) {
            if (empty($pid)) {
                continue;
            }
            $pids .= $pid . ",";
        }
        foreach ($vidArray as $vid) {
            if (empty($vid)) {
                continue;
            }
            $vids .= $vid . ",";
        }
        if (!empty($pids)) {
            $pids = substr($pids, 0, strlen($pids) - 1);
        }
        if (!empty($vids)) {
            $vids = substr($vids, 0, strlen($vids) - 1);
        }
    }

    /**
     * 获取sku所对应的属性值
     *
     * @param int $sku_id
     * @return string
     */
    public function getSkuPropValues($sku_id)
    {
        if (empty($sku_id)) {
            return "";
        }
        $propValueNames = "";
        $fasthandItemSkuService = new \FasthandItemSkuService();
        $fasthandItemPropValueService = new \FasthandItemPropValueService();
        $fasthand_item_sku = new \Fasthand_item_sku();
        $fasthand_item_sku->setId($sku_id);
        $fasthandItemSkuService->loadByID($fasthand_item_sku);
        $properties = $fasthand_item_sku->getProperties();
        if (empty($properties)) {
            return "";
        }
        $propertieArray = split(ITEM_PROP_SEPARATOR, $properties);
        $vids = "";
        foreach ($propertieArray as $propertie) {
            $propertieItemArray = split(ITEM_PROP_VALUE_SEPARATOR, $propertie);
            if (!empty($propertieItemArray) && count($propertieItemArray) > 1) {
                $vid = $propertieItemArray[1];
            }
            $vids .= $vid . ",";
        }
        if (!empty($vids)) {
            $vids = substr($vids, 0, strlen($vids) - 1);
            $fasthand_item_prop_valueQuery = new \Fasthand_item_prop_valueQuery();
            $fasthand_item_prop_valueQuery->setIds($vids);
            $fasthand_item_prop_valueArray = $fasthandItemPropValueService->listByquery($fasthand_item_prop_valueQuery);
            foreach ($fasthand_item_prop_valueArray as $fasthand_item_prop_value) {
                $name = $fasthand_item_prop_value->getName();
                $propValueNames .= $name . "/";
            }
            if (!empty($propValueNames)) {
                $propValueNames = substr($propValueNames, 0, strlen($propValueNames) - 1);
            }
        }
        return $propValueNames;
    }

    /**
     * 创建订单快照
     *
     * @param array $request
     * @param int $seller_id
     * @param int $seller_user_id
     * @return string
     */
    public function createItemSnapshotObject(array $request, $seller_id, $seller_user_id)
    {
        global $order_courses_type_value_array, $fasthand_teacher_type_array;
        $type = is_array($request) ? $request['type'] : $request->get("type", "");
        $event_id = is_array($request) ? $request['event_id'] : $request->get("event_id", "");
        $sku_id = is_array($request) ? $request['sku_id'] : $request->get("sku_id", "");
        $snapshotObject = array();
//		$snapshotObjectJson = "";
        $fasthandItemPropBeanArray = array();//属性列表
        if (!empty($sku_id)) {
            //获取sku属性 第一个存储
            $fasthandItemPropBean = new \FasthandItemPropBean();
            $fasthandItemPropBean->setName(ORDER_SKU_PROPS);
            $showValue = $this->getSkuPropValues($sku_id);
            $fasthandItemPropBean->setShowValue($showValue);
            array_push($fasthandItemPropBeanArray, $fasthandItemPropBean);
        }
        if ($type == "courses") {
            //课程 获取机构名、机构图片、课程名、课程原价、课程授课老师、是否试题课
            $is_listen = is_array($request) ? $request['is_listen'] : $request->get("is_listen", "");//是否试听：1，是；0，否
            $fasthand_courses = $this->mGetCoursesInfoById($event_id);
            $courses_name = $fasthand_courses->getTitle();
            $snapshotObject['name'] = $courses_name;
            $teacherId = $fasthand_courses->getTeacher_id();
            $institutionId = $fasthand_courses->getInstitution_id();
            $seller_id = $institutionId;
            $seller_user_id = $fasthand_courses->getUser_id();
            $location_id = '';
            $imagesUrl = $fasthand_courses->getImage_urls();;
            if (!empty($imagesUrl)) {
                $imagesUrlArray = Util::json_url_decode($imagesUrl);
                $snapshotObject['image_url'] = $imagesUrlArray[0];
            }
            if (!empty($institutionId)) {
                $fasthand_institution = $this->mGetInstitutionInfoById($institutionId);
                $event_name = $fasthand_institution->getName();
                $image_url = $fasthand_institution->getHead_portrait();
                $mobile = $fasthand_institution->getPhone_num();
//				$location_id = $fasthand_institution->getLocation_id();
                $snapshotObject['event_name'] = $event_name;
                if (!isset($snapshotObject['image_url'])) {
                    $snapshotObject['image_url'] = $image_url;
                }
                $snapshotObject['mobile'] = $mobile;
            }
            if (!empty($teacherId)) {
                $fasthand_institution_teacher = $this->mGetInstitutionTeacherInfoById($teacherId);
                if (!empty($fasthand_institution_teacher)) {
                    $institution_teacher_name = $fasthand_institution_teacher->getName();
                    $snapshotObject['institution_teacher_name'] = $institution_teacher_name;
                    $tmp_institution_id = $fasthand_institution_teacher->getInstitution_id();
                    $fasthand_institution = $this->mGetInstitutionInfoById($tmp_institution_id);
                    $location_id = $fasthand_institution->getLocation_id();
                }
            }
            $fasthandItemPropBean = new \FasthandItemPropBean();
            $fasthandItemPropBean->setName(ORDER_COURSES_TYPE_KEY);
            if (empty($is_listen)) {
                $showValue = $order_courses_type_value_array["2"];
            } else {
                $showValue = $order_courses_type_value_array["1"];
            }
            $fasthandItemPropBean->setShowValue($showValue);
            array_push($fasthandItemPropBeanArray, $fasthandItemPropBean);
        } elseif ($type == "activity") {
            $fasthand_activity = $this->mGetActivityInfoById($event_id);
            $activity_name = $fasthand_activity->getName();
            $event_type = $fasthand_activity->getType();
            $event_id = $fasthand_activity->getEvent_id();
            $snapshotObject['name'] = $activity_name;
            $seller_id = $event_id;
            $seller_user_id = $fasthand_activity->getUser_id();
            if ($event_type == "institution") {
                $fasthand_institution = $this->mGetInstitutionInfoById($event_id);
                $event_name = $fasthand_institution->getName();
                $image_url = $fasthand_institution->getHead_portrait();
                $mobile = $fasthand_institution->getPhone_num();
            } elseif ($event_type == "teacher") {
                $fasthand_user = $this->mGetPublicUserInfo($seller_user_id);
                $event_name = $fasthand_user->getNick();
                $image_url = $fasthand_user->getHead_portrait();
                $mobile = $fasthand_user->getMobile();
            } else {
                $fasthand_user = $this->mGetPublicUserInfo($seller_user_id);
                $event_name = $fasthand_user->getNick();
                $image_url = $fasthand_user->getHead_portrait();
                $mobile = $fasthand_user->getMobile();
            }
            $snapshotObject['event_name'] = $event_name;
            $snapshotObject['image_url'] = $image_url;
            $snapshotObject['mobile'] = $mobile;

            //属性列表
//			$is_adult = $fasthand_activity->getIs_adult();
//			$is_child = $fasthand_activity->getIs_child();
//			$is_package = $fasthand_activity->getIs_package();
            $package_num = is_array($request) ? $request['package_num'] : $request->get("package_num", "");//活动购买的套餐数量，默认0
            $number = is_array($request) ? $request['number'] : $request->get("number", "");//活动购买的套餐数量，默认0
            $contacts = is_array($request) ? $request['contacts'] : $request->get("contacts", "");//联系人
            $mobile = is_array($request) ? $request['mobile'] : $request->get("mobile", "");//联系电话
            if (!empty($number)) {
                $fasthandItemPropBean = new \FasthandItemPropBean();
                $fasthandItemPropBean->setName(ORDER_ACTIVITY_TYPE_NUMBER);
                $fasthandItemPropBean->setShowValue($number);
                array_push($fasthandItemPropBeanArray, $fasthandItemPropBean);
            } elseif (!empty($package_num)) {
                $fasthandItemPropBean = new \FasthandItemPropBean();
                $fasthandItemPropBean->setName(ORDER_ACTIVITY_TYPE_NUMBER);
                $fasthandItemPropBean->setShowValue($package_num);
                array_push($fasthandItemPropBeanArray, $fasthandItemPropBean);
            }
            if (!empty($contacts)) {
                $fasthandItemPropBean = new \FasthandItemPropBean();
                $fasthandItemPropBean->setName(ORDER_ACTIVITY_TYPE_CONTACTS);
                $fasthandItemPropBean->setShowValue($contacts);
                array_push($fasthandItemPropBeanArray, $fasthandItemPropBean);
            }
            if (!empty($mobile)) {
                $fasthandItemPropBean = new \FasthandItemPropBean();
                $fasthandItemPropBean->setName(ORDER_ACTIVITY_TYPE_MOBILE);
                $fasthandItemPropBean->setShowValue($mobile);
                array_push($fasthandItemPropBeanArray, $fasthandItemPropBean);
            }
        } elseif ($type == "teacher") {
            //教师获取教师名、是否试听、购买数量
            $is_listen = is_array($request) ? $request['is_listen'] : $request->get("is_listen", "");//是否试听：1，是；0，否
            $number = is_array($request) ? $request['number'] : $request->get("number", "");//购买数量
            $fasthand_teacher = $this->mGetTeacherInfoById($event_id);
            $name = $fasthand_teacher->getTeacher_nick();
            $teacherType = $fasthand_teacher->getTeacher_type();
            $event_name = "";
            if (!empty($teacherType)) {
                $event_name = $fasthand_teacher_type_array[$teacherType];
            }
            $snapshotObject['name'] = $name;
            $seller_id = $event_id;
            $seller_user_id = $fasthand_teacher->getUser_id();
            if (!empty($seller_user_id)) {
                $fasthand_user = $this->mGetPublicUserInfo($seller_user_id);
                $image_url = $fasthand_user->getHead_portrait();
                $mobile = $fasthand_user->getMobile();
                $snapshotObject['event_name'] = $event_name;
                $snapshotObject['image_url'] = $image_url;
                $snapshotObject['mobile'] = $mobile;
            }

            $fasthandItemPropBean = new \FasthandItemPropBean();
            $fasthandItemPropBean->setName(ORDER_COURSES_TYPE_KEY);
            if (empty($is_listen)) {
                $showValue = $order_courses_type_value_array["2"];
                $showValue .= " " . $number . "节";
            } else {
                $showValue = $order_courses_type_value_array["1"];
            }
// 			$fasthandItemPropBean->setShowValue($showValue);
// 			$fasthandItemPropBean = new \FasthandItemPropBean();
// 			$fasthandItemPropBean->setName(ORDER_BUY_NUMBER_KEY);
            $fasthandItemPropBean->setShowValue($showValue);
            array_push($fasthandItemPropBeanArray, $fasthandItemPropBean);
        }

        $snapshotObject['propList'] = $fasthandItemPropBeanArray;
        $snapshotObjectJson = \Util::json_url_encode($snapshotObject);
        return $snapshotObjectJson;
    }

    /**
     * 更新活动的属性值
     *
     * @param int $id
     * @param int $sku_id
     * @param string $properties
     * @return bool
     */
    public function updateActivityProps($id, $sku_id, $properties)
    {
        $fasthandActivityService = new \FasthandActivityService();
        $fasthandItemSkuService = new \FasthandItemSkuService();
        $fasthand_item_skuQuery = new \Fasthand_item_skuQuery();
        $fasthand_item_skuQuery->setEvent_id($id);
        $fasthand_item_skuQuery->setEvent_type(ORDER_TYPE_ACTIVITY);
        $fasthand_item_skuArray = $fasthandItemSkuService->listByquery($fasthand_item_skuQuery);
        $fasthand_activity = new \Fasthand_activity();
        $fasthand_activity->setId($id);
        $fasthandActivityService->loadByID($fasthand_activity);
        $activityProps = $fasthand_activity->getProps();
        foreach ($fasthand_item_skuArray as $fasthand_item_sku) {
            //判断属性值是否已经存在
            $tmpProperties = $fasthand_item_sku->getProperties();
            $tmpSkuId = $fasthand_item_sku->getId();
            if ($tmpSkuId != $sku_id) {
                if ($tmpProperties == $properties) {
                    return false;
                }
            }
        }
        if (!empty($activityProps)) {
            $pids = "";
            $vids = "";
            $this->getPropValueByProps($activityProps, $pids, $vids);
        }
        $submitPids = "";
        $submitVids = "";
        $this->getPropValueByProps($properties, $submitPids, $submitVids);
        if (!empty($pids)) {
            //判断新的pid是否包含在原有活动属性中
            $pidArray = split(",", $pids);
            $submitPidArray = split(",", $submitPids);
            $pidArray = array_unique($pidArray);
            sort($pidArray);
            sort($submitPidArray);
            $sortPids = "";
            $sortSubmitPids = "";
            foreach ($pidArray as $tmpPid) {
                if (!empty($tmpPid)) {
                    $sortPids .= $tmpPid . ",";
                }
            }
            foreach ($submitPidArray as $tmpPid) {
                if (!empty($tmpPid)) {
                    $sortSubmitPids .= $tmpPid . ",";
                }
            }
            if ($sortPids != $sortSubmitPids) {
                //新传递的pid与原有活动pid不同，不允许传递
                return false;
            }
        }
        $activityProps = $this->createPropsBySkus($fasthand_item_skuArray, $properties);
        $fasthand_activity->setProps($activityProps);
        $fasthandActivityService->update($fasthand_activity);
        return true;
    }

    /**
     * 通过sku数组生成props
     *
     * @param array $fasthand_item_skuArray
     * @param string $addProperties
     * @return string
     */
    public function createPropsBySkus(array $fasthand_item_skuArray, $addProperties = "")
    {
        $props = "";
        $activityPropArray = array();
        foreach ($fasthand_item_skuArray as $fasthand_item_sku) {
            $properties = $fasthand_item_sku->getProperties();
            $propertieArray = split(";", $properties);
            foreach ($propertieArray as $propertie) {
                if (!in_array($propertie, $activityPropArray)) {
                    array_push($activityPropArray, $propertie);
                }
            }
        }
        if (!empty($addProperties)) {
            $addPropertieArray = split(";", $addProperties);
            foreach ($addPropertieArray as $addPropertie) {
                if (!in_array($addPropertie, $activityPropArray)) {
                    array_push($activityPropArray, $addPropertie);
                }
            }
        }
        foreach ($activityPropArray as $prop) {
            $props .= $prop . ";";
        }
        if (!empty($props)) {
            $props = substr($props, 0, strlen($props) - 1);
        }
        return $props;

    }

    /**
     * 检查优惠领取状态
     *
     * @param int $userId
     * @param int $event_id
     * @param string $event_type
     * @return int
     */
    public function checkPromotionReceiveStatus($userId, $event_id, $event_type)
    {
        if (empty($userId) || empty($event_type) || empty($event_type)) {
            return false;
        }
        $fasthandMyPromotionService = new \FasthandMyPromotionService();
        $fasthand_my_promotionQuery = new \Fasthand_my_promotionQuery();
        $fasthand_my_promotionQuery->setUser_id($userId);
        $fasthand_my_promotionQuery->setOnlyEventId("," . $event_id . ",");
        $fasthand_my_promotionQuery->setItem_type($event_type);
        $fasthand_my_promotionQuery->setStatus("1");
        $fasthand_my_promotionQuery->setBegin_time(date("Y-m-d H:i:s"));
        $fasthand_my_promotionQuery->setEnd_time(date("Y-m-d H:i:s"));
        $total = $fasthandMyPromotionService->countByQuery($fasthand_my_promotionQuery);
        if ($total > 0) {
            return "1";
        }
        return "0";
    }

    /**
     * 添加消费后验证码返佣记录
     *
     * @param string $invite_code
     * @param int $userId
     * @param float $pay_amount
     * @param int $event_id
     * @return bool
     */
    public function addConsumIncomeRecord($invite_code, $userId, $pay_amount, $event_id)
    {
        $fasthandUserService = new \FasthandUserService();
        $fasthand_user = $this->checkInviteCodeStatus($invite_code, $userId);
        $result = false;
        if (!empty($fasthand_user)) {
            $inviteUserId = $fasthand_user->getId();

            $amount = intval($pay_amount * COMMISSION_PROPORTION_NUM_BUY);
            $amount = $amount / 100;
            if ($amount > 0) {
                $this->addMyIncomeRecord($userId, $amount, INCOME_TITLE_COMMISSION_BUY, INCOME_TYPE_COMMISSION_BUY,
                    $userId, "1", $event_id, "order", "1");//邀请码给自己返现
                $mobile = $fasthand_user->getUsername();
                if (empty($mobile) || !Util::checkMobile($mobile)) {
                    $mobile = $fasthand_user->getMobile();
                }
                if (!empty($mobile)) {
                    $smsUtil = new \SmsUtil();
                    $content = sprintf(MessageCode::$sms_content_buy_commission, $amount);
                    $sendContent = iconv("UTF-8", "GBK", $content);
                    $smsUtil->setDa("86" . $mobile);
                    $smsUtil->setSm($sendContent);
                    $sendResult = $smsUtil->singleMt();
                    $smsUtil->saveSms($sendResult, $content, $mobile);
                }
            }
            $fasthand_user_query = new \Fasthand_user();
            $fasthand_user_query->setId($userId);
            $fasthandUserService->loadByID($fasthand_user_query);
            $nick = $fasthand_user_query->getNick();
            $amount = intval($pay_amount * COMMISSION_PROPORTION_NUM_PROVIDER);
            $amount = $amount / 100;
            if ($amount > 0) {
                $result = $this->addMyIncomeRecord($inviteUserId, $amount, INCOME_TITLE_COMMISSION_PROVIDER . "（" . $nick . "）",
                    INCOME_TYPE_COMMISSION_PROVIDER, $userId, "1", $event_id, "order", "1");//邀请码给邀请人返现
                $mobile = $fasthand_user_query->getUsername();
                if (empty($mobile) || !Util::checkMobile($mobile)) {
                    $mobile = $fasthand_user_query->getMobile();
                }
                if (!empty($mobile)) {
                    $smsUtil = new \SmsUtil();
                    $content = sprintf(MessageCode::$sms_content_buy_commission_provider, $amount);
                    $sendContent = iconv("UTF-8", "GBK", $content);
                    $smsUtil->setDa("86" . $mobile);
                    $smsUtil->setSm($sendContent);
                    $sendResult = $smsUtil->singleMt();
                    $smsUtil->saveSms($sendResult, $content, $mobile);
                }
            }
        }
        return $result;

    }

    /**
     * 检查邀请码状态
     *
     * @param string $invite_code
     * @param int $userId
     * @return bool
     */
    public function checkInviteCodeStatus($invite_code, $userId)
    {
        if (empty($userId) || empty($invite_code)) {
            return false;
        }
        $fasthandUserService = new \FasthandUserService();
        $fasthand_userQuery = new \Fasthand_userQuery();
        $fasthand_userQuery->setInvite_code($invite_code);
        $fasthand_userQuery->setNotId($userId);
        $fasthand_userArray = $fasthandUserService->listByquery($fasthand_userQuery);
        $result = false;
        if (!empty($fasthand_userArray) && count($fasthand_userArray) > 0) {
            $fasthand_user = $fasthand_userArray[0];
            return $fasthand_user;
        }
        return false;
    }

    /**
     * 获取我的收入记录
     *
     * @param int $userId
     * @param float $amount
     * @param string $title
     * @param int $type
     * @param int $origin_user_id
     * @param int $status //1,审核通过；2，审核中；3，审核拒绝
     * @param int $event_id
     * @param string $event_type
     * @param string $action //动作：1，增加收入；2，减少收入
     * @param array $fasthand_user //操作者的用户信息
     * @return bool
     */
    public function addMyIncomeRecord($userId, $amount, $title, $type, $origin_user_id, $status,
                                      $event_id, $event_type, $action = "2", array $fasthand_user = null)
    {
        $fasthandMyIncomeService = new \FasthandMyIncomeService();
        $fasthandUserService = new \FasthandUserService();
        if (empty($fasthand_user)) {
            $fasthand_user = new \Fasthand_user();
            $fasthand_user->setId($userId);
            $fasthandUserService->loadByID($fasthand_user);
        }
        $income_num = $fasthand_user->getIncome_num();
        if ($action == "1") {
            $income_num = $income_num + $amount;
        } else {
            $income_num = $income_num - $amount;
            $amount = 0 - $amount;
        }
        if ($income_num < 0) {
            return false;
        }
        $fasthand_my_income = new \Fasthand_my_income();
        $fasthand_my_income->setAmount($amount);
        $fasthand_my_income->setCreate_time(date("Y-m-d H:i:s"));
        $fasthand_my_income->setOrigin_user_id($origin_user_id);
        $fasthand_my_income->setTitle($title);
        $fasthand_my_income->setUpdate_time(date("Y-m-d H:i:s"));
        $fasthand_my_income->setUser_id($userId);
        $fasthand_my_income->setStatus($status);
        $fasthand_my_income->setType($type);
        $fasthand_my_income->setEvent_id($event_id);
        $fasthand_my_income->setEvent_type($event_type);
        $fasthandMyIncomeService->add($fasthand_my_income);
        $fasthand_user->setIncome_num($income_num);
        $fasthandUserService->update($fasthand_user);
        return true;
    }

    /**
     * 通过课程id获取课程信息
     *
     * @param int $id
     * @return Fasthand_courses
     */
    public function mGetCoursesInfoById($id)
    {
        $fasthandCoursesService = new \FasthandCoursesService();
        $fasthand_courses = new \Fasthand_courses();
        $fasthand_courses->setId($id);
        $fasthandCoursesService->loadByID($fasthand_courses);
        return MyUtil::object2array($fasthand_courses);
    }

    /**
     * 通过活动id获取活动信息
     * @param int $id
     * @return Fasthand_activity
     */
    public function mGetActivityInfoById($id)
    {
        $fasthandActivityService = new \FasthandActivityService();
        $fasthand_activity = new \Fasthand_activity();
        $fasthand_activity->setId($id);
        $fasthandActivityService->loadByID($fasthand_activity);
        return $fasthand_activity;
    }

    /**
     * 通过机构id获取机构信息
     *
     * @param int $id
     * @return Fasthand_institution
     */
    public function mGetInstitutionInfoById($id)
    {
        $fasthandInstitutionService = new \FasthandInstitutionService();
        $fasthand_institution = new \Fasthand_institution();
        $fasthand_institution->setId($id);
        $fasthandInstitutionService->loadByID($fasthand_institution);
        return $fasthand_institution;
    }

    /**
     * 通过机构教师id获取信息
     *
     * @param int $id
     * @return Fasthand_institution_teacher
     */
    public function mGetInstitutionTeacherInfoById($id)
    {
        $fasthandInstitutionTeacherService = new \FasthandInstitutionTeacherService();
        $fasthand_institution_teacher = new \Fasthand_institution_teacher();
        $fasthand_institution_teacher->setId($id);
        $fasthandInstitutionTeacherService->loadByID($fasthand_institution_teacher);
        return $fasthand_institution_teacher;

    }

    /**
     * 通过教师id获取教师信息
     *
     * @param int $id
     * @return Fasthand_teacher
     */
    public function mGetTeacherInfoById($id)
    {
        $fasthandTeacherService = new \FasthandTeacherService();
        $fasthand_teacher = new \Fasthand_teacher();
        $fasthand_teacher->setId($id);
        $fasthandTeacherService->loadByID($fasthand_teacher);
        return $fasthand_teacher;
    }

    /**
     * 获取用户信息
     *
     * @param int $userId
     * @return Fasthand_user
     */
    public function mGetPublicUserInfo($userId)
    {
        $fasthandUserService = new \FasthandUserService();
        $fasthand_user = new \Fasthand_user();
        $fasthand_user->setId($userId);
        $fasthandUserService->loadByID($fasthand_user);
        return $fasthand_user;
    }

    /**
     * 创建消费码
     * 生成消费码算法：月日+5位随机数
     *
     * @param string $event_id
     * @param string $event_type
     * @return string
     */
    public function createConsumCode($event_id = "", $event_type = "")
    {

        if (!empty($event_type) && !empty($event_id)) {
            //检查是否存在已经配置的消费码
            $fasthandOrderConsumcodeService = new \FasthandOrderConsumcodeService();
            $fasthand_order_consumcodeQuery = new \Fasthand_order_consumcodeQuery();
            $fasthand_order_consumcodeQuery->setEvent_id($event_id);
            $fasthand_order_consumcodeQuery->setEvent_type($event_type);
            $fasthand_order_consumcodeQuery->setStatus("1");
            $fasthand_order_consumcodeArray = $fasthandOrderConsumcodeService->listByqueryTopage($fasthand_order_consumcodeQuery, 0, 1);
            if (!empty($fasthand_order_consumcodeArray) && count($fasthand_order_consumcodeArray) >= 1) {
                $fasthand_order_consumcode = $fasthand_order_consumcodeArray[0];
                $consumCode = $fasthand_order_consumcode->getConsum_code();
                $fasthand_order_consumcode->setStatus("2");
                $fasthand_order_consumcode->setUpdate_time("Y-m-d H:i:s");
                $fasthandOrderConsumcodeService->update($fasthand_order_consumcode);
                return $consumCode;
            }
        }
        while (true) {
            $time = date("md");
            $randNum = rand(0, 999999);
            $randNum = sprintf("%06d", $randNum);
            $consumCode = $randNum . $time;
            $consumCode = sprintf("%010d", $consumCode);
            $fasthandOrderService = new \FasthandOrderService();
            $fasthand_orderQuery = new \Fasthand_orderQuery();
            $fasthand_orderQuery->setConsum_code($consumCode);
            $total = $fasthandOrderService->countByQuery($fasthand_orderQuery);
            if ($total <= 0) {
                return $consumCode;
            }
        }
        return null;
    }

    /**
     * 获取红包列表
     *
     * @param int $event_id
     * @param string $event_type //支持商品的事件类型
     * @param int $userId
     * @param bool $notType //不支持的优惠类型
     * @param bool $is_allow_promotion //是否支持使用优惠
     * @return array
     */
    public function getPromotionList($event_id, $event_type, $userId, $notType = false, $is_allow_promotion = true)
    {
        global $fasthand_promotion_discount_array;
        $fasthandItemPromotionBeanArray = array();
        $fasthand_item_promotionArray = array();
        if (empty($is_allow_promotion)) {
            //不允许使用优惠
            return $this->mGetNotAllowPromotionList();
        }
        $fasthandMyPromotionService = new \FasthandMyPromotionService();
        $fasthandItemPromotionService = new \FasthandItemPromotionService();
        $fasthand_my_promotionQuery = new \Fasthand_my_promotionQuery();
        $fasthand_my_promotionQuery->setEvent_ids("," . $event_id . ",");
        $fasthand_my_promotionQuery->setEvent_id($event_id);
        $fasthand_my_promotionQuery->setItem_type($event_type);
        $fasthand_my_promotionQuery->setStatus("1");
        $fasthand_my_promotionQuery->setBegin_time(date("Y-m-d"));
        $fasthand_my_promotionQuery->setEnd_time(date("Y-m-d"));
        $fasthand_my_promotionQuery->setUser_id($userId);
        if (!empty($notType)) {
            $fasthand_my_promotionQuery->setNotTypes($notType);
        }
        $fasthand_my_promotionArray = $fasthandMyPromotionService->listByquery($fasthand_my_promotionQuery);
        if (count($fasthand_my_promotionArray) > 0) {
            foreach ($fasthand_my_promotionArray as $fasthand_my_promotion) {
                $promotion_type = $fasthand_my_promotion->getType();
                $amount = $fasthand_my_promotion->getAmount();
                if (in_array($promotion_type, $fasthand_promotion_discount_array)) {
                    $my_promotion_type = "2";
                    $amount = intval($amount);
                } else {
                    $my_promotion_type = "1";
                }
                $fasthandItemPromotionBean = new \FasthandItemPromotionBean();
                $fasthandItemPromotionBean->setName($fasthand_my_promotion->getName());
                $fasthandItemPromotionBean->setId($fasthand_my_promotion->getId());
                $fasthandItemPromotionBean->setAmount("" . $amount);
                $fasthandItemPromotionBean->setPromotion_type($my_promotion_type);
                array_push($fasthandItemPromotionBeanArray, $fasthandItemPromotionBean);
            }
            $max_amount = 0;
            $selectedItem = array();
            $selectedId = 0;
            foreach ($fasthandItemPromotionBeanArray as $fasthandItemPromotionBean) {
                $amount = $fasthandItemPromotionBean->getAmount();
                $promotion_type = $fasthandItemPromotionBean->getPromotion_type();
                if ($promotion_type == "2") {
                    //优先显示折扣券
                    $selectedItem = $fasthandItemPromotionBean;
                    $selectedId = $fasthandItemPromotionBean->getId();
                    break;
                }
                if ($amount > $max_amount) {
                    $max_amount = $amount;
                    $selectedItem = $fasthandItemPromotionBean;
                    $selectedId = $fasthandItemPromotionBean->getId();
                }
            }
            $returnArray = array();
            array_push($returnArray, $selectedItem);
            foreach ($fasthandItemPromotionBeanArray as $tmpFasthandItemPromotionBean) {
                $tmpId = $tmpFasthandItemPromotionBean->getId();
                if ($tmpId != $selectedId) {
                    array_push($returnArray, $tmpFasthandItemPromotionBean);
                }
            }
            $fasthandItemPromotionBean = new \FasthandItemPromotionBean();
            $fasthandItemPromotionBean->setId("0");
            $fasthandItemPromotionBean->setName(PROMOTION_SELECT_CONTENT);
            array_push($returnArray, $fasthandItemPromotionBean);
            return $returnArray;
        } else {
            $fasthandItemPromotionBean = new \FasthandItemPromotionBean();
            $fasthandItemPromotionBean->setId("0");
            $fasthandItemPromotionBean->setName(PROMOTION_NOT_CONTENT);
            array_push($fasthandItemPromotionBeanArray, $fasthandItemPromotionBean);
            return $fasthandItemPromotionBeanArray;
        }

    }

    /**
     * 获取用户剩余最大购买量
     *
     * @param int $event_id
     * @param string $event_type
     * @param int $max_buy_num
     * @param int $userId
     * @return string
     */
    public function getMaxBuyNum($event_id, $event_type, $max_buy_num, $userId)
    {
        if (empty($max_buy_num)) {
            return ORDER_MAX_BUY_NUM;
        }
        $fasthandOrderService = new \FasthandOrderService();
        $fasthand_orderQuery = new \Fasthand_orderQuery();
        $fasthand_orderQuery->setEvent_id($event_id);
        $fasthand_orderQuery->setType($event_type);
        $fasthand_orderQuery->setUser_id($userId);
        $fasthand_orderQuery->setInStatus(ORDER_STATUS_WAIT_PAY . "," . ORDER_STATUS_PAY . "," . ORDER_STATUS_CONSUME);
        $total = 0;
        $fasthandOrderArray = $fasthandOrderService->listByQuery($fasthand_orderQuery);
        foreach ($fasthandOrderArray as $fasthandOrder) {
            $number = $fasthandOrder->getNumber();
            $total += $number;
        }
        $max_buy_num = $max_buy_num - $total;
        if ($max_buy_num < 0) {
            $max_buy_num = 0;
        }
        return "" . $max_buy_num;
    }


    /**
     * 判断用户的是否超过最大购买限制
     *
     * @param array $itemOriginVo
     * @param int $event_id
     * @param string $event_type
     * @param int $userId
     * @param int $number
     * @return bool
     */
    public function checkUserMaxBuyNum(array $itemOriginVo, $event_id, $event_type, $userId, $number)
    {
        if (!empty($itemOriginVo)) {
            if ($event_type == ORDER_TYPE_ACTIVITY) {
                $max_buy_num = $itemOriginVo->getMax_buy_num();
            } elseif ($event_type == ORDER_TYPE_COURSES) {
                $max_buy_num = $itemOriginVo->getMax_buy_num();
            }
        }
        $max_buy_num = $this->getMaxBuyNum($event_id, $event_type, $max_buy_num, $userId);
        if ($max_buy_num >= $number) {
            return true;
        }
        return false;

    }

    /**
     * 通过event_id和event_type商品的来源Vo（如活动、教师和课程Vo）
     *
     * @param int $event_id
     * @param string $type
     * @return string
     */
    public function getItemOriginVo($event_id, $type)
    {
        $itemVo = null;
        switch ($type) {
            case ORDER_TYPE_TEACHER:
                $itemVo = BaseLogicService::getPublicTeacherInfoById($event_id);
                break;
            case ORDER_TYPE_COURSES:
                $itemVo = BaseLogicService::getPublicCoursesInfoById($event_id);
                break;
            case ORDER_TYPE_ACTIVITY:
                $itemVo = BaseLogicService::getPublicActivityInfoById($event_id);
                break;
            default:
                break;
        }
        return $itemVo;
    }

    /**
     * 判断是否是打折券类型红包
     *
     * @param array $fasthand_item_promotion
     * @param string $pid
     * @return string
     */
    public function checkIsDiscount(array $fasthand_item_promotion, $pid = '0')
    {
        global $fasthand_promotion_discount_array;
        $is_discount = '0';
        if (empty($fasthand_item_promotion)) {
            return $is_discount;
        }
        $type = $fasthand_item_promotion->getType();
        if (in_array($type, $fasthand_promotion_discount_array) && empty($pid)) {
            $is_discount = "1";
        }
        return $is_discount;
    }

    /**
     * 获取不允许使用优惠
     *
     * @return array
     */
    public function mGetNotAllowPromotionList()
    {
        $fasthandItemPromotionBeanArray = array();
        $fasthandItemPromotionBean = new \FasthandItemPromotionBean();
        $fasthandItemPromotionBean->setId("0");
        $fasthandItemPromotionBean->setName(PROMOTION_NOT_ALLOW_CONTENT);
        array_push($fasthandItemPromotionBeanArray, $fasthandItemPromotionBean);
        return $fasthandItemPromotionBeanArray;
    }

    /**
     * 通过skuId获取sku信息
     *
     * @param int $sku_id
     * @return array 
     */
    public function getSkuInfoById($sku_id)
    {
        $fasthandItemSkuService = new \FasthandItemSkuService();
        $fasthand_item_sku = new \Fasthand_item_sku();
        $fasthand_item_sku->setId($sku_id);
        $fasthandItemSkuService->loadByID($fasthand_item_sku);
        return $fasthand_item_sku;
    }

    /**
     * 获取课程信息的商品化结构
     *
     * @param int $coursesId
     * @param int $userId
     * @return array 
     */
    public function getItemByCourses($coursesId, $userId)
    {
        $fasthand_courses = BaseLogicService::getPublicCoursesInfoById($coursesId);
        if (!$fasthand_courses) {
            return null;
        }
        //最大购买量
        $max_buy_num = $fasthand_courses->getMax_buy_num();
        if (!empty($max_buy_num)) {
            $max_buy_num = $this->getMaxBuyNum($fasthand_courses->getId(), ORDER_TYPE_COURSES, $max_buy_num, $userId);
            $is_max_buy = "1";
        } else {
            $max_buy_num = ORDER_MAX_BUY_NUM;
            $is_max_buy = "0";
        }
        //地址
        $locationId = $fasthand_courses->getLocation_id();
        $fasthand_location = BaseLogicService::getPublicLocationInfoById($locationId);
        $address = $fasthand_location ? $fasthand_location->getAddress() : "";
        //商品图片及提供商名字
        $institutionId = $fasthand_courses->getInstitution_id();
        $imgUrls = $fasthand_courses->getImage_urls();
        $imgUrlsArray = Util::json_url_decode($imgUrls);
        $fasthand_institution = BaseLogicService::getPublicInstitutionInfoById($institutionId);
        if ($imgUrlsArray && count($imgUrlsArray) > 0) {
            //课程有图片，取课程图片作为商品图片
            $head_portrait = $imgUrlsArray[0];
        } else if ($fasthand_institution) {
            //否则取机构图片
            $head_portrait = $fasthand_institution->getHead_portrait();
        } else {
            $head_portrait = "";
        }
        //$institutionName = $fasthand_instutiton ? $fasthand_instutiton->getName() : "";

        $fasthandItemBean = new \FasthandItemBean();
        $fasthandItemBean->setEvent_id($coursesId);
        $fasthandItemBean->setIs_installment($fasthand_courses->getIs_installment());
        $fasthandItemBean->setIs_listen($fasthand_courses->getIs_listen());
        $fasthandItemBean->setName($fasthand_courses->getTitle());
        $fasthandItemBean->setPrice($fasthand_courses->getDiscount_price());
        $fasthandItemBean->setType(ORDER_TYPE_COURSES);
        $fasthandItemBean->setInventory_num($fasthand_courses->getInventory_num());
        $fasthandItemBean->setIs_allow_integral($fasthand_courses->getIs_allow_integral());
        $fasthandItemBean->setMax_buy_num($max_buy_num);
        $fasthandItemBean->setIs_allow_promotion($fasthand_courses->getIs_allow_promotion());
        $fasthandItemBean->setIs_max_buy($is_max_buy);
        $fasthandItemBean->setSize_num("1");
        $fasthandItemBean->setAddress($address);
        $fasthandItemBean->setHead_portrait($head_portrait);

        $fasthand_institution = new \Fasthand_institution();
        $fasthand_institution->setId($fasthand_courses->getInstitution_id());
        $fasthandInstitutionService = new \FasthandInstitutionService();
        $fasthandInstitutionService->loadByID($fasthand_institution);
        $fasthandItemBean->setInstitutionName($fasthand_institution->getName());

        return $fasthandItemBean;
    }

    /**
     * 获取活动信息的商品化结构(不包含prop信息)
     *
     * @param int $activityId
     * @param int $userId
     * @return array
     */
    public function getItemByActivity($activityId, $userId)
    {
        global $fasthand_activity_package_type_array;
        $fasthand_activity = BaseLogicService::getPublicActivityInfoById($activityId);
        if (!$fasthand_activity) {
            return null;
        }
        $max_buy_num = $fasthand_activity->getMax_buy_num();
        $package_type = $fasthand_activity->getPackage_type();
        $price = $fasthand_activity->getPackage_price();//5.4.0后套餐价为活动价格
        if (!empty($package_type)) {
            $size_num = $fasthand_activity_package_type_array[$package_type];
        } else {
            $size_num = "1";
        }
        if (!empty($max_buy_num)) {
            $max_buy_num = $this->getMaxBuyNum($fasthand_activity->getId(), ORDER_TYPE_ACTIVITY, $max_buy_num, $userId);
            $is_max_buy = "1";
        } else {
            $max_buy_num = ORDER_MAX_BUY_NUM;
            $is_max_buy = "0";
        }
        $fasthandItemBean = new \FasthandItemBean();
        $fasthandItemBean->setEvent_id($activityId);
        $fasthandItemBean->setIs_installment($fasthand_activity->getIs_installment());
        $fasthandItemBean->setName($fasthand_activity->getName());
        $fasthandItemBean->setInventory_num($fasthand_activity->getInventory_num());
        $fasthandItemActivityBean = new \FasthandItemActivityBean();
        $fasthandItemActivityBean->copyProperties($fasthand_activity);
        $fasthandItemBean->setActivityInfo($fasthandItemActivityBean);
        $fasthandItemBean->setType(ORDER_TYPE_ACTIVITY);
        $fasthandItemBean->setIs_allow_integral($fasthand_activity->getIs_allow_integral());
        $fasthandItemBean->setMax_buy_num($max_buy_num);
        $fasthandItemBean->setIs_allow_promotion($fasthand_activity->getIs_allow_promotion());
        $fasthandItemBean->setIs_max_buy($is_max_buy);
        $fasthandItemBean->setSize_num($size_num);
        $fasthandItemBean->setPrice($price);
        return $fasthandItemBean;
    }

    /**
     * 获取活动信息的商品化结构(包含prop信息)
     *
     * @param int $activityId
     * @param int $userId
     * @return array
     */
    public function getItemAndPropByActivity($activityId, $userId)
    {
        global $fasthand_activity_package_type_array;
        $fasthand_activity = BaseLogicService::getPublicActivityInfoById($activityId);
        if (!$fasthand_activity) {
            return null;
        }
        $max_buy_num = $fasthand_activity->getMax_buy_num();
        $package_type = $fasthand_activity->getPackage_type();
        $price = $fasthand_activity->getPackage_price();//5.4.0后套餐价为活动价格
        if (!empty($package_type)) {
            $size_num = $fasthand_activity_package_type_array[$package_type]['size'];
        }
        $size_num = $size_num ? $size_num : "1";
        //最大购买量
        if (!empty($max_buy_num)) {
            $max_buy_num = $this->getMaxBuyNum($fasthand_activity->getId(), ORDER_TYPE_ACTIVITY, $max_buy_num, $userId);
            $is_max_buy = "1";
        } else {
            $max_buy_num = ORDER_MAX_BUY_NUM;
            $is_max_buy = "0";
        }
        //商品图片及提供商名字
        $event_id = $fasthand_activity->getEvent_id();
        $event_type = $fasthand_activity->getType();
        switch ($event_type) {
            case "institution":
                $fasthand_institution = BaseLogicService::getPublicInstitutionInfoById($event_id);
                if ($fasthand_institution) {
                    $event_name = $fasthand_institution->getName();
                    $img_url = $fasthand_institution->getHead_portrait();
                }
                break;
            case "teacher":
                $fasthand_teacher = BaseLogicService::getPublicTeacherInfoById($event_id);
                if ($fasthand_teacher) {
                    $event_name = $fasthand_teacher->getTeacher_nick();
                    $teacherUserId = $fasthand_teacher->getUser_id();
                    $fasthand_user = BaseLogicService::getPublicUserInfoById($teacherUserId);
                    if ($fasthand_user) {
                        $img_url = $fasthand_user->getHead_portrait();
                    }
                }
                break;
            default :
                $fasthand_user = BaseLogicService::getPublicUserInfoById($event_id);
                if ($fasthand_user) {
                    $img_url = $fasthand_user->getHead_portrait();
                    $event_name = $fasthand_user->getNick();
                }
                break;
        }

        $fasthandItemBean = new \FasthandItemBean();
        $fasthandItemBean->setEvent_id($activityId);
        $fasthandItemBean->setIs_installment($fasthand_activity->getIs_installment());
        $fasthandItemBean->setName($fasthand_activity->getName());
        $fasthandItemBean->setInventory_num($fasthand_activity->getInventory_num());
        $fasthandItemActivityBean = new \FasthandItemActivityBean();
        $fasthandItemActivityBean->copyProperties($fasthand_activity);
        $fasthandItemBean->setActivityInfo($fasthandItemActivityBean);
        $fasthandItemBean->setType(ORDER_TYPE_ACTIVITY);
        $fasthandItemBean->setIs_allow_integral($fasthand_activity->getIs_allow_integral());
        $fasthandItemBean->setMax_buy_num($max_buy_num);
        $fasthandItemBean->setIs_allow_promotion($fasthand_activity->getIs_allow_promotion());
        $fasthandItemBean->setIs_max_buy($is_max_buy);
        $fasthandItemBean->setSize_num($size_num);
        $fasthandItemBean->setPrice($price);
        $fasthandItemBean->setInstitutionName($event_name);
        $fasthandItemBean->setHead_portrait($img_url);

        //sku处理
        $props = $fasthand_activity->getProps();
        $pids = "";
        $vids = "";
        $pidNameArray = array();
        $fasthand_item_propArray = array();
        $fasthandItemPropValueBeanArray = array();
        //获取sku的pid和vid串
        $this->getPropValueByProps($props, $pids, $vids);
        if (!empty($pids)) {
            $fasthand_item_propArray = $this->mGetItemPropDoListByPids($pids);
            foreach ($fasthand_item_propArray as $fasthand_item_prop) {
                $pidNameArray[$fasthand_item_prop->getId()] = $fasthand_item_prop->getName();
            }
        }
        if (!empty($vids)) {
            $fasthandItemPropValueBeanArray = $this->getItemPropValueListByVids($vids, $pidNameArray);
        }
        //获取商品属性信息
        $fasthandItemPropBeanArray = $this->getItemPropBeanList($fasthand_item_propArray, $fasthandItemPropValueBeanArray);
        //获取sku信息
        $extendInfoArray = array();
        $extendInfoArray['prop'] = $fasthand_item_propArray;
        $extendInfoArray['propValue'] = $fasthandItemPropValueBeanArray;
        $fasthandSkuBeanList = $this->getItemSkuList($activityId, ORDER_TYPE_ACTIVITY, $extendInfoArray);
        if (empty($fasthandSkuBeanList) || count($fasthandSkuBeanList) == 0) {
            $fasthandSkuBeanList = array();
            $fasthandItemSkuBean = $this->addSkuInfoByActivity($fasthand_activity);
            $fasthandSkuBeanList[] = $fasthandItemSkuBean;
        }
        $fasthandItemBean->setItemPropList($fasthandItemPropBeanArray);
        $fasthandItemBean->setSkuList($fasthandSkuBeanList);
        return $fasthandItemBean;
    }

    /**
     * 获取商品属性值列表
     *
     * @param string $vids
     * @param array $pidNameArray
     * @return array
     */
    public function getItemPropValueListByVids($vids, array $pidNameArray = array())
    {
        if (empty($vids)) {
            return array();
        }
        $fasthandItemPropValueBeanArray = array();
        $fasthandItemPropValueService = new \FasthandItemPropValueService();
        $fasthand_item_prop_valueQuery = new \Fasthand_item_prop_valueQuery();
        $fasthand_item_prop_valueQuery->setIds($vids);
        $fasthand_item_prop_valueArray = $fasthandItemPropValueService->listByquery($fasthand_item_prop_valueQuery);
        foreach ($fasthand_item_prop_valueArray as $fasthand_item_prop_value) {
            $fasthandItemPropValueBean = new \FasthandItemPropValueBean();
            $fasthandItemPropValueBean->copyProperties($fasthand_item_prop_value);
            $pid = $fasthandItemPropValueBean->getPid();
            $prop_name = $pidNameArray[$pid];
            $fasthandItemPropValueBean->setProp_name($prop_name);
            array_push($fasthandItemPropValueBeanArray, $fasthandItemPropValueBean);
        }
        return $fasthandItemPropValueBeanArray;
    }

    /**
     * 通过pid获取商品属性列表
     *
     * @param string $pids
     * @return array
     */
    public function mGetItemPropDoListByPids($pids)
    {
        $fasthandItemPropBeanArray = array();

        $fasthandItemPropService = new \FasthandItemPropService();
        $fasthand_item_propQuery = new \Fasthand_item_propQuery();
        $fasthand_item_propQuery->setIds($pids);
        $fasthand_item_propQuery->setStatus(PUBLIC_STATUS_ONLINE);
        $fasthand_item_propArray = $fasthandItemPropService->listByquery($fasthand_item_propQuery);
        return $fasthand_item_propArray;

    }

    /**
     * 获取商品属性Bean列表
     *
     * @param array $fasthand_item_propArray
     * @param array $fasthandItemPropValueBeanArray
     * @return array
     */
    public function getItemPropBeanList(array $fasthand_item_propArray, array $fasthandItemPropValueBeanArray)
    {
        $fasthandItemPropBeanArray = array();
        $itemPropValueBeanArray = array();
        foreach ($fasthandItemPropValueBeanArray as $fasthandItemPropValueBean) {
            $pid = $fasthandItemPropValueBean->getPid();
            $itemPropValueBeanArray[$pid][] = $fasthandItemPropValueBean;
        }
        foreach ($fasthand_item_propArray as $fasthand_item_prop) {
            $fasthandItemPropBean = new \FasthandItemPropBean();
            $fasthandItemPropBean->copyProperties($fasthand_item_prop);
            $pid = $fasthand_item_prop->getId();
            if ($itemPropValueBeanArray && isset($itemPropValueBeanArray[$pid])) {
                $fasthandItemPropBean->setValueList($itemPropValueBeanArray[$pid]);
            }
            $fasthandItemPropBeanArray[] = $fasthandItemPropBean;
        }
        return $fasthandItemPropBeanArray;
    }

    /**
     * 获取教师信息的商品化结构
     *
     * @param int $teacherId
     * @return array
     */
    public function getItemByTeacher($teacherId)
    {
        $fasthand_teacher = BaseLogicService::getPublicTeacherInfoById($teacherId);
        if (!$fasthand_teacher) {
            return null;
        }
        $fasthandItemBean = new \FasthandItemBean();
        $userId = $fasthand_teacher->getUser_id();
        $fasthand_user = BaseLogicService::getPublicUserInfoById($userId);
        if ($fasthand_user) {
            $fasthandItemBean->setHead_portrait($fasthand_user->getHead_portrait());
        }
        $fasthandItemBean->setEvent_id($teacherId);
        $fasthandItemBean->setIs_installment("0");
        $fasthandItemBean->setName($fasthand_teacher->getTeacher_nick());
        $fasthandItemBean->setInventory_num($fasthand_teacher->getInventory_num());
        $fasthandItemBean->setType(ORDER_TYPE_TEACHER);
        $fasthandItemBean->setIs_listen($fasthand_teacher->getIs_lecture());
        $fasthandItemBean->setPrice($fasthand_teacher->getFee());
        $fasthandItemBean->setMax_buy_num(ORDER_MAX_BUY_NUM);
        $fasthandItemBean->setIs_max_buy("0");
        $fasthandItemBean->setSize_num("1");
        return $fasthandItemBean;
    }

    /**
     * 获取支付价格
     *
     * @param int $event_id
     * @param string $type
     * @param int $userId
     * @param int $number
     * @param int $promotion_id
     * @param float $discount_rate
     * @return array
     */
    public function getPayPrice($event_id, $type, $userId, $number = '1', $promotion_id = '', $discount_rate = "")
    {
        $payPrice = 0;
        $returnPriceArray = array();
        if ($type == ORDER_TYPE_COURSES) {
            $fasthandItemBean = $this->getItemByCourses($event_id, $userId);
            $price = $fasthandItemBean->getPrice();
            $payPrice = $price * $number;
        } elseif ($type == ORDER_TYPE_TEACHER) {
            $fasthandItemBean = $this->getItemByTeacher($event_id, $userId);
            $price = $fasthandItemBean->getPrice();
            $payPrice = $price * $number;
        } elseif ($type == ORDER_TYPE_ACTIVITY) {
            $fasthandItemBean = $this->getItemByActivity($event_id, $userId);
            $price = $fasthandItemBean->getPrice();
            $payPrice = $price * $number;
        }
        $returnPriceArray['item_amount'] = "" . $payPrice;
        if (!empty($discount_rate)) {
            $discountRateArray = $this->getInstitutionDiscountRate($event_id);
            $is_discount_rate = $discountRateArray['is_ji_discount'];
            $discount_rate = $discountRateArray['ji_discount_rate'];
            if (!empty($is_discount_rate) && !empty($discount_rate)) {
                $promotionPrice = $price * ((100 - $discount_rate) / 100);
                $returnPriceArray['promotion_amount'] = "" . $promotionPrice;
                $payPrice = $payPrice - $promotionPrice;
                if ($payPrice <= 0) {
                    $payPrice = "0";
                }
            }
        } elseif (!empty($promotion_id)) {
            $fasthandMyPromotionService = new \FasthandMyPromotionService();
            $fasthand_my_promotionQuery = new \Fasthand_my_promotionQuery();
            $fasthand_my_promotionQuery->setId($promotion_id);
            $fasthand_my_promotionQuery->setUser_id($userId);
            $fasthand_my_promotionArray = $fasthandMyPromotionService->listByquery($fasthand_my_promotionQuery);
            if (!empty($fasthand_my_promotionArray) && count($fasthand_my_promotionArray) > 0) {
                $fasthand_my_promotion = $fasthand_my_promotionArray[0];
                $amount = $fasthand_my_promotion->getAmount();
                $type = $fasthand_my_promotion->getType();
                $promotionPrice = $this->getPromotionPrice($type, $payPrice, $amount);
                $returnPriceArray['promotion_amount'] = "" . $promotionPrice;
                $payPrice = $payPrice - $promotionPrice;
                if ($payPrice <= 0) {
                    $payPrice = "0";
                }
            }
        }
        $returnPriceArray['pay_amount'] = "" . $payPrice;
        return $returnPriceArray;
    }

    /**
     * 获取Sku支付价格
     *
     * @param int $event_id
     * @param string $type
     * @param int $sku_id
     * @param int $userId
     * @param int $number
     * @param int $promotion_id
     * @param float $discount_rate
     * @return array
     */
    public function getSkuPayPrice($event_id, $type, $sku_id, $userId, $number = '1', $promotion_id = '', $discount_rate = "")
    {
        $returnPriceArray = array();
        $fasthandItemSkuService = new \FasthandItemSkuService();
        $fasthand_item_sku = new \Fasthand_item_sku();
        $fasthand_item_sku->setId($sku_id);
        $fasthandItemSkuService->loadByID($fasthand_item_sku);
        $price = $fasthand_item_sku->getPrice();
        $payPrice = $price * $number;
        $returnPriceArray['item_amount'] = "" . $payPrice;
        if (!empty($discount_rate)) {
            $discountRateArray = $this->getInstitutionDiscountRate($event_id);
            $is_discount_rate = $discountRateArray['is_ji_discount'];
            $discount_rate = $discountRateArray['ji_discount_rate'];
            if (!empty($is_discount_rate) && !empty($discount_rate)) {
                $promotionPrice = $price * ((100 - $discount_rate) / 100);
                $returnPriceArray['promotion_amount'] = "" . $promotionPrice;
                $payPrice = $payPrice - $promotionPrice;
                if ($payPrice <= 0) {
                    $payPrice = "0";
                }
            }
        } else if (!empty($promotion_id)) {
            $fasthandMyPromotionService = new \FasthandMyPromotionService();
            $fasthand_my_promotionQuery = new \Fasthand_my_promotionQuery();
            $fasthand_my_promotionQuery->setId($promotion_id);
            $fasthand_my_promotionQuery->setUser_id($userId);
            $fasthand_my_promotionArray = $fasthandMyPromotionService->listByquery($fasthand_my_promotionQuery);
            if (!empty($fasthand_my_promotionArray) && count($fasthand_my_promotionArray) > 0) {
                $fasthand_my_promotion = $fasthand_my_promotionArray[0];
                $amount = $fasthand_my_promotion->getAmount();
                $type = $fasthand_my_promotion->getType();
                $promotionPrice = $this->getPromotionPrice($type, $payPrice, $amount);
                $returnPriceArray['promotion_amount'] = "" . $promotionPrice;
                $payPrice = $payPrice - $promotionPrice;
                if ($payPrice <= 0) {
                    $payPrice = "0";
                }
            }
        }
        $returnPriceArray['pay_amount'] = "" . $payPrice;
        return $returnPriceArray;
    }

    /**
     * 获取活动支付价格
     *
     * @param int $event_id
     * @param int $userId
     * @param int $promotion_id
     * @param int $adult_num
     * @param int $child_num
     * @param int $package_num
     * @return float
     */
    public function getActivityPayPrice($event_id, $userId, $promotion_id, $adult_num, $child_num, $package_num)
    {
        $payPrice = 0;
        $fasthandActivityService = new \FasthandActivityService();
        $fasthand_activity = new \Fasthand_activity();
        $fasthand_activity->setId($event_id);
        $fasthandActivityService->loadByID($fasthand_activity);
        $adult_price = $fasthand_activity->getAdult_price();
        $is_adult = $fasthand_activity->getIs_adult();
        $is_child = $fasthand_activity->getIs_child();
        $is_package = $fasthand_activity->getIs_package();
        $child_price = $fasthand_activity->getChild_price();
        $package_price = $fasthand_activity->getPackage_price();
        $payPrice = 0;
        if (!empty($is_adult)) {
            $payPrice = $adult_num * $adult_price;
        }
        if (!empty($is_child)) {
            $payPrice = $payPrice + ($child_price * $child_num);
        }
        if (!empty($is_package)) {
            $payPrice = $payPrice + ($package_num * $package_price);
        }
        if (!empty($promotion_id)) {
            $fasthandMyPromotionService = new \FasthandMyPromotionService();
            $fasthand_my_promotionQuery = new \Fasthand_my_promotionQuery();
            $fasthand_my_promotionQuery->setId($promotion_id);
            $fasthand_my_promotionQuery->setUser_id($userId);
            $fasthand_my_promotionArray = $fasthandMyPromotionService->listByquery($fasthand_my_promotionQuery);
            if (!empty($fasthand_my_promotionArray) && count($fasthand_my_promotionArray) > 0) {
                $fasthand_my_promotion = $fasthand_my_promotionArray[0];
                $amount = $fasthand_my_promotion->getAmount();
                $type = $fasthand_my_promotion->getType();
                $promotionPrice = $this->getPromotionPrice($type, $payPrice, $amount);
                $payPrice = $payPrice - $promotionPrice;
                if ($payPrice <= 0) {
                    $payPrice = "0";
                }
            }
        }
        return $payPrice;
    }

    /**
     * 获取活动套餐包的大小
     *
     * @param int $id
     * @return int
     */
    public function getActivityPackageSizeById($id)
    {
        global $fasthand_activity_package_type_array;
        $fasthandActivtyService = new \FasthandActivityService();
        $fasthand_activity = new \Fasthand_activity();
        $fasthand_activity->setId($id);
        $fasthandActivtyService->loadByID($fasthand_activity);
        $package_type = $fasthand_activity->getPackage_type();
        if (empty($package_type)) {
            $package_type = "1";
        }
        $fasthand_activity_package_type = $fasthand_activity_package_type_array[$package_type];
        $size = $fasthand_activity_package_type['size'];
        return $size;
    }

    /**
     * 通过余额支付
     *
     * @param array $fasthand_order
     * @param array $fasthand_user
     * @param string $message
     * @return bool
     */
    public function payOrderByIncome(array $fasthand_order, array $fasthand_user, $message)
    {
        $fasthandOrderService = new \FasthandOrderService();
        $fasthandUserService = new \FasthandUserService();
        $status = $fasthand_order->getStatus();
        $income_num = $fasthand_user->getIncome_num();
        $eventId = $fasthand_order->getEvent_id();
        $eventType = $fasthand_order->getType();
        $number = $fasthand_order->getNumber();
        $userId = $fasthand_user->getId();
        if ($status == ORDER_STATUS_WAIT_PAY) {
            $pay_amount = $fasthand_order->getPay_amount();
            if ($income_num >= $pay_amount) {
                $fasthand_order->setStatus(ORDER_STATUS_PAY);
                $fasthand_order->setPay_time(date("Y-m-d H:i:s"));
                $fasthand_order->setPay_type(ORDER_PAY_TYPE_INCOME);
                $fasthand_order->setPay_object("");
                $fasthand_order->setUpdate_time(date("Y-m-d H:i:s"));
                $fasthand_order->setTrade_no("");
                $fasthandOrderService->update($fasthand_order);
                $this->addOrderHistory($fasthand_order);
                $this->payOrderSuccessEvent($fasthand_order, $number, $userId);
                $this->addMyIncomeRecord($userId, $pay_amount, INCOME_TITLE_ORDER_PAY, INCOME_TYPE_ORDER_PAY,
                    $userId, "1", $fasthand_order->getId(), "order", "2");//余额付款写入收入表
                return true;
            } else {
                $message = "您的账户余额不够支付此订单！";

            }
        } else {
            $message = "您付款的订单不存在！";
        }
        return false;
    }

    /**
     * 领取优惠券
     *
     * @param int $promotionId
     * @param int $userId
     * @param string $mobile
     * @param string $end_time
     */
    public function receivePromotion($promotionId, $userId, $mobile, $end_time = "")
    {
        $fasthandMyPromotionService = new \FasthandMyPromotionService();
        $fasthandItemPromotionService = new \FasthandItemPromotionService();

        if (!empty($promotionId)) {
            $fasthand_item_promotion = new \Fasthand_item_promotion();
            $fasthand_item_promotion->setId($promotionId);
            $fasthandItemPromotionService->loadByID($fasthand_item_promotion);
        }
        if (empty($fasthand_item_promotion)) {
            return;
        }
        $promotionEndTime = $fasthand_item_promotion->getEnd_time();
        if (empty($end_time) || $end_time > $promotionEndTime) {
            $end_time = $promotionEndTime;
        }
        $fasthand_my_promotion = new \Fasthand_my_promotion();
        $fasthand_my_promotion->setAmount($fasthand_item_promotion->getAmount());
        $fasthand_my_promotion->setBegin_time(date("Y-m-d H:i:s"));
        $fasthand_my_promotion->setCreate_time(date("Y-m-d H:i:s"));
        $fasthand_my_promotion->setEnd_time($end_time);
        $fasthand_my_promotion->setEvent_id($fasthand_item_promotion->getEvent_id());
        $fasthand_my_promotion->setItem_type($fasthand_item_promotion->getItem_type());
        $fasthand_my_promotion->setName($fasthand_item_promotion->getName());
        $fasthand_my_promotion->setStatus(ORDER_MY_PROMOTION_STATUS_GET);
        $fasthand_my_promotion->setType($fasthand_item_promotion->getType());
        $fasthand_my_promotion->setUpdate_time(date("Y-m-d H:i:s"));
        $fasthand_my_promotion->setUse_condition($fasthand_item_promotion->getUse_condition());
        $fasthand_my_promotion->setUser_id($userId);
        $fasthand_my_promotion->setPromotion_id($fasthand_item_promotion->getId());
        $fasthand_my_promotion->setMobile($mobile);
        $fasthandMyPromotionService->add($fasthand_my_promotion);
        $receive_num = $fasthand_item_promotion->getReceive_num();
        $receive_num = $receive_num + 1;
        $fasthand_item_promotion->setReceive_num($receive_num);
        $fasthandItemPromotionService->update($fasthand_item_promotion);
    }

    /**
     * 支付订单成功后事件
     *
     * @param array $fasthand_order
     * @param int $number
     * @param int $userId
     */
    public function payOrderSuccessEvent(array $fasthand_order, $number, $userId)
    {
        $event_id = $fasthand_order->getEvent_id();
        $event_type = $fasthand_order->getType();
        $this->updatePayNum($fasthand_order->getEvent_id(), $fasthand_order->getType(), $number);//更改成功支付数量
        $this->sendPaySmsToSeller($fasthand_order, $userId);//给卖家发送短信
        $this->sendPaySmsToStudent($fasthand_order);//给学生发送短信
        $friendLogicService = new \FriendLogicService();
        $friendLogicService->addFeedEvent("", $event_id, $event_type, $userId, "pay");//添加朋友圈消息
    }

    /**
     * 获取直接折扣优惠比例
     *
     * @param int $coursesId
     * @param array $fasthand_institution
     * @return array
     */
    public function getInstitutionDiscountRate($coursesId, array $fasthand_institution = null)
    {
        $returnArray = array();
        if (empty($fasthand_institution)) {
            $fasthand_courses = new \Fasthand_courses();
            $fasthandCoursesService = new \FasthandCoursesService();
            $fasthandInstitutionService = new \FasthandInstitutionService();
            $fasthand_courses->setId($coursesId);
            $result = $fasthandCoursesService->loadByID($fasthand_courses);
            if ($result) {
                $institutionId = $fasthand_courses->getInstitution_id();
                $fasthand_institution = new \Fasthand_institution();
                $fasthand_institution->setId($institutionId);
                $fasthandInstitutionService->loadByID($fasthand_institution);
                $is_ji_discount = $fasthand_institution->getIs_ji_discount();
                $ji_discount_rate = $fasthand_institution->getJi_discount_rate();
                $returnArray['is_ji_discount'] = $is_ji_discount;
                $returnArray['ji_discount_rate'] = $ji_discount_rate;
            }
        }
        return $returnArray;
    }

    /**
     * AJAX检查订单
     *
     * @param array $paramArray
     * @return array 
     */
    public function ajaxCheckOrder(array $paramArray)
    {
        $userId = $paramArray['userId'];
        $event_id = $paramArray["event_id"]; //产生商品的事件编号，如课程编号或活动编号
        $type = $paramArray['type']; //商品类型：teacher，教师；courses，课程；activity，活动
        $my_promotion_id = $paramArray['my_promotion_id']; //所使用的我领取的优惠编号，应该是my_promotion_id
        $buy_number = $paramArray['number']; //提交的购买数量
        $is_listen = $paramArray['is_listen']; //是否试听：1，是；0，否
        $describes = $paramArray['describes']; //给卖家留言
        $integral_num = $paramArray['integral_num'];//使用的积分数量，不用积分不传递
        $invite_code = $paramArray['invite_code'];//邀请码
        $sku_id = $paramArray['sku_id'];//商品的sku编号
        $certificates = $paramArray['certificates'];//证件信息，传递json串，所有证件信息定义字典数组,字典格式"name"=>"姓名","number"=>"110110198701010011"

        if (!$userId || !$event_id || !$type) {
            $errCodeArray = ResultCode::get("paramError");
            return new \FasthandResponse($errCodeArray['code'], $errCodeArray['message']);
        }
        $invite_code = strtoupper($invite_code);
        $buy_number = $buy_number ? $buy_number : 1;
        $fasthandOrderService = new \FasthandOrderService();

        if (mb_strlen($describes, "UTF-8") > DESCRIBE_MAXLEN) {
            return new \FasthandResponse(400, "留言字数超过最大限制！");
        }

        if ($invite_code) {
            $inviteStatusResult = $this->checkInviteCodeStatus($invite_code, $userId);//判断邀请码状态
            if (empty($inviteStatusResult)) {
                return new \FasthandResponse(400, "您的邀请码输入错误！");
            }
        }
        //判断优惠是否可用
        if ($my_promotion_id) {
            $promotion_status = self::checkPromotionStatus($my_promotion_id, $userId);
            if (!$promotion_status) {
                return new \FasthandResponse(400, "此优惠券不能使用！");
            }
        }


        $fasthand_item_sku = BaseLogicService::getPublicItemSkuInfoById($sku_id);
        if ($fasthand_item_sku) {
            $package_size = $fasthand_item_sku->getSize_num();
        } else if ($type == ORDER_TYPE_ACTIVITY) {
            $package_size = $this->getActivityPackageSizeById($event_id);
        } else {
            $package_size = 1;
        }
        $number = $buy_number * $package_size;//商品的总件数
        $itemOriginVo = $this->getItemOriginVo($event_id, $type);//获取产生商品源vo
        $checkMaxBuyNum = $this->checkUserMaxBuyNum($itemOriginVo, $event_id, $type, $userId, $number);//判断最大购买数状态
        if (!$checkMaxBuyNum) {
            return new \FasthandResponse(400, "您已超过购买限额！");
        }
        if ($is_listen && $type != ORDER_TYPE_ACTIVITY) {
            //试听课价格为0，直接变成支付状态
            $db_pay_price = 0.0;
        } else {
            //判断库存状态
            if ($fasthand_item_sku) {
                $inventory_num = $fasthand_item_sku->getInventory_num();
                $checkInventoryResult = $inventory_num < $number ? false : true;
            } else {
                $checkInventoryResult = $this->checkInventoryStatus($event_id, $event_type, $number, $itemOriginVo);
            }
            if (!$checkInventoryResult) {
                return new \FasthandResponse(400, "库存不足,请电询！");
            }
        }
        return new \FasthandResponse(0, "成功！");
    }

    /**
     * 下单
     *
     * @param array $paramArray
     * @return array 
     */
    public function createOrder(array $paramArray)
    {
        $userId = $paramArray['userId'];
        $event_id = $paramArray["event_id"]; //产生商品的事件编号，如课程编号或活动编号
        $type = $paramArray['type']; //商品类型：teacher，教师；courses，课程；activity，活动
        $my_promotion_id = $paramArray['my_promotion_id']; //所使用的我领取的优惠编号，应该是my_promotion_id
        $buy_number = $paramArray['number']; //提交的购买数量
        $is_listen = $paramArray['is_listen']; //是否试听：1，是；0，否
        $describes = $paramArray['describes']; //给卖家留言
        $integral_num = $paramArray['integral_num'];//使用的积分数量，不用积分不传递
        $invite_code = $paramArray['invite_code'];//邀请码
        $sku_id = $paramArray['sku_id'];//商品的sku编号
        $certificates = $paramArray['certificates'];//证件信息，传递json串，所有证件信息定义字典数组,字典格式"name"=>"姓名","number"=>"110110198701010011"

        if (!$userId || !$event_id || !$type) {
            $errCodeArray = ResultCode::get("paramError");
            return new \FasthandResponse($errCodeArray['code'], $errCodeArray['message']);
        }
        $invite_code = strtoupper($invite_code);
        $buy_number = $buy_number ? $buy_number : 1;
        $fasthandOrderService = new \FasthandOrderService();

        if (mb_strlen($describes, "UTF-8") > DESCRIBE_MAXLEN) {
            return new \FasthandResponse(400, "留言字数超过最大限制！");
        }
        if (!empty($certificates)) {
            //将证件信息备份到给卖家留言中
            $certificates = Util::replateStandardToShow($certificates);
            $certificatesDesc = $this->transcodeCertificatesToDescribes($certificates);
            $describes .= "\n\r" . $certificatesDesc;
        }

        if ($invite_code) {
            $inviteStatusResult = $this->checkInviteCodeStatus($invite_code, $userId);//判断邀请码状态
            if (empty($inviteStatusResult)) {
                return new \FasthandResponse(400, "您的邀请码输入错误！");
            }
        }
        //判断优惠是否可用
        if ($my_promotion_id) {
            $promotion_status = self::checkPromotionStatus($my_promotion_id, $userId);
            if (!$promotion_status) {
                return new \FasthandResponse(400, "此优惠券不能使用！");
            }
        }

        $fasthand_item_sku = BaseLogicService::getPublicItemSkuInfoById($sku_id);
        if ($fasthand_item_sku) {
            $package_size = $fasthand_item_sku->getSize_num();
        } else if ($type == ORDER_TYPE_ACTIVITY) {
            $package_size = $this->getActivityPackageSizeById($event_id);
        } else {
            $package_size = 1;
        }
        $number = $buy_number * $package_size;//商品的总件数
        $itemOriginVo = $this->getItemOriginVo($event_id, $type);//获取产生商品源vo
        $checkMaxBuyNum = $this->checkUserMaxBuyNum($itemOriginVo, $event_id, $type, $userId, $number);//判断最大购买数状态
        if (!$checkMaxBuyNum) {
            return new \FasthandResponse(400, "您已超过购买限额！");
        }
        if ($is_listen && $type != ORDER_TYPE_ACTIVITY) {
            //试听课价格为0，直接变成支付状态
            $db_pay_price = 0.0;
        } else {
            //判断库存状态
            if ($fasthand_item_sku) {
                $inventory_num = $fasthand_item_sku->getInventory_num();
                $checkInventoryResult = $inventory_num < $number ? false : true;
            } else {
                $checkInventoryResult = $this->checkInventoryStatus($event_id, $event_type, $number, $itemOriginVo);
            }
            if (!$checkInventoryResult) {
                return new \FasthandResponse(400, "库存不足,请电询！");
            }
            //计算价格
            if (!empty($sku_id)) {
                $payArray = $this->getSkuPayPrice($event_id, $type, $sku_id, $userId, $buy_number, $my_promotion_id);
            } else {
                $payArray = $this->getPayPrice($event_id, $type, $userId, $buy_number, $my_promotion_id);
            }
            if (!empty($payArray)) {
                $db_pay_price = $payArray['pay_amount'];
                $item_amount = $payArray['item_amount'];
                $promotion_amount = $payArray['promotion_amount'];
            }
        }
        $integral_price = 0.0;
        if (!empty($integral_num)) {
            //使用积分，计算积分递减金额
            $integral_price = $this->getOrderIntegralConvertPrice($userId, $db_pay_price, $integral_num);
            $db_pay_price = $db_pay_price - $integral_price;
            $db_pay_price = $db_pay_price < 0.0 ? 0.0 : $db_pay_price;
        }
        //此次购买消费的积分数
        $consume_integral_num = $integral_price > 0.0 ? $integral_price * 100 : 0;

        if (empty($db_pay_price)) {
            //价格为0，订单状态为已支付
            $status = ORDER_STATUS_PAY;
            $pay_type = ORDER_PAY_TYPE_FREE;
            $pay_time = date("Y-m-d H:i:s");
        } else {
            $status = ORDER_STATUS_WAIT_PAY;
            $pay_type = "";
            $pay_time = "";
        }

        $seller_id = "";
        $seller_user_id = "";
        $consum_code = $this->createConsumCode($event_id, $type);
        $item_snapshot_json = $this->createItemSnapshotObject($paramArray, $seller_id, $seller_user_id);
        $out_trade_no = md5($consum_code);

        $fasthand_order = new \Fasthand_order();
        $fasthand_order->setConsum_code($consum_code);
        $fasthand_order->setCreate_time(date("Y-m-d H:i:s"));
        $fasthand_order->setEvent_id($event_id);
        $fasthand_order->setItem_snapshot($item_snapshot_json);
        $fasthand_order->setPay_amount($db_pay_price);
        $fasthand_order->setPromotion_id($my_promotion_id);
        $fasthand_order->setStatus($status);
        $fasthand_order->setPay_time($pay_time);
        $fasthand_order->setType($type);
        $fasthand_order->setUpdate_time(date("Y-m-d H:i:s"));
        $fasthand_order->setSeller_id($seller_id);
        $fasthand_order->setUser_id($userId);
        $fasthand_order->setOut_trade_no($out_trade_no);
        $fasthand_order->setPay_type($pay_type);
        $fasthand_order->setDescribes($describes);
        $fasthand_order->setNumber($number);
        $fasthand_order->setIntegral_num($consume_integral_num);
        $fasthand_order->setInvite_code($invite_code);
        $fasthand_order->setPromotion_amount($promotion_amount);
        $fasthand_order->setItem_amount($item_amount);
        $fasthandOrderService->add($fasthand_order);
        $this->orderEvent($fasthand_order, $seller_user_id);
        return new \FasthandResponse(0, "下单成功！", array("orderInfo" => $fasthand_order));
    }

    /**
     * 添加订单后的事件
     *
     * @param array $fasthand_order
     * @param int $seller_user_id
     * @return bool
     */
    public function orderEvent(array $fasthand_order, $seller_user_id)
    {
        if (!$fasthand_order) return false;
        $userId = $fasthand_order->getUser_id();
        $seller_id = $fasthand_order->getSeller_id();
        $type = $fasthand_order->getType();
        $consume_integral_num = $fasthand_order->getIntegral_num();
        $my_promotion_id = $fasthand_order->getPromotion_id();
        $db_pay_price = $fasthand_order->getPay_amount();

        $baseLogicService = new \BaseLogicService();
        if ($type == ORDER_TYPE_TEACHER) {
            $baseLogicService->plusInstitutionStatistic($userId, "order", "0", $seller_id);
        } else {
            $baseLogicService->plusInstitutionStatistic($userId, "order", $seller_id, "0");
        }
        $this->addOrderHistory($fasthand_order);
        //添加积分消费历史
        if ($consume_integral_num > 0) {
            $consume_integral_num = 0 - $consume_integral_num;
            $userLogicService = new \UserLogicService();
            $userLogicService->addNativeIntegral("15", $userId, $consume_integral_num);
        }
        if (!empty($my_promotion_id)) {
            $this->updatePromotionStatus($my_promotion_id, ORDER_MY_PROMOTION_STATUS_CONSUME);
        }
        if (empty($db_pay_price)) {
            $this->payOrderSuccessEvent($fasthand_order, $number, $userId);
        } else {
            $role = $type == "courses" ? MessageCode::$message_role_institution : MessageCode::$message_role_all;
            $invalidTime = "2080-01-01 00:00:00";
            $dataArray = array("type" => "orderList");
            self::addMessage("0", "", MessageCode::$message_content_neworder, date("Y-m-d H:i:s"), $invalidTime, MessageCode::$message_type_personal,
                MessageCode::$message_title_neworder, $seller_user_id, $role, $dataArray);
            $this->updateInventoryNumber($fasthand_order, "1");//修改库存量
        }
        return true;
    }

    /**
     * 取消订单
     *
     * @param array $paramArray
     * @return array
     */
    public function closeOrder(array $paramArray)
    {
        $userId = $paramArray['userId'];
        $orderId = $paramArray['orderId'];
        if (!$orderId || !$userId) {
            $errCodeArray = ResultCode::get("paramError");
            return new \FasthandResponse($errCodeArray['code'], $errCodeArray['message']);
        }
        $fasthandOrderService = new \FasthandOrderService();
        $fasthand_order = new \Fasthand_order();
        $fasthand_order->setId($orderId);
        $result = $fasthandOrderService->loadByID($fasthand_order);
        if ($result) {
            $dbUserId = $fasthand_order->getUser_id();
            $status = $fasthand_order->getStatus();
            $integral_num = $fasthand_order->getIntegral_num();
            $promotion_id = $fasthand_order->getPromotion_id();
            if ($status == ORDER_STATUS_WAIT_PAY && $userId == $dbUserId) {
                $fasthand_order->setStatus(ORDER_STATUS_CLOSE);
                $fasthand_order->setUpdate_time(date("Y-m-d H:i:s"));
                $fasthandOrderService->update($fasthand_order);
                $this->addOrderHistory($fasthand_order);
                //返还积分
                if (!empty($integral_num)) {
                    $userLogicService = new \UserLogicService();
                    $userLogicService->addNativeIntegral("16", $userId, $integral_num);
                }
                //返回优惠
                if (!empty($promotion_id)) {
                    $this->updatePromotionStatus($promotion_id, ORDER_MY_PROMOTION_STATUS_GET);
                }
                $this->updateInventoryNumber($fasthand_order, "2");//修改库存量
                return new \FasthandResponse(0, "订单取消成功！");
            } else {
                return new \FasthandResponse(400, "订单状态已改变！");
            }
        } else {
            return new \FasthandResponse(400, "订单不存在！");
        }
    }
}

?>
