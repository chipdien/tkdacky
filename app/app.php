<?php

class App extends F3instance {
    
    public $userid = '';
    public $username = '';
    private $ajaxRequest = false;


    function homepage() {
        $this->set('NewbiePresent', $this->_gotNewbiePresent());
        $this->set('pageTitle', 'Tổng Quan');
        $this->set('template', 'main');
    }
    
    function present() {
        $this->set('Present', $this->_countOfficalPosPresent());
        $this->set('pageTitle', 'Nhận Thưởng');
        $this->set('template', 'present');
    }
    
    function shop() {
        $this->set('pageTitle', 'Chợ Trời');
        $this->set('template', 'shop');
        
    }
    
    function profile() {
        $this->set('pageTitle', 'Trang Cá Nhân');
        $this->set('template', 'profile');
    }

    function login() {
        $this->clear('SESSION');
        $this->set('pageTitle', 'Đăng Nhập');
        $this->set('template', 'login');
    }
    
    function auth() {
        $this->clear('message');
        
        if(isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password']) ) {
            $_POST['username'] = addslashes($_POST['username']);
            $_POST['password'] = addslashes($_POST['password']);
            $_POST['rememberMe'] = (int)$_POST['rememberMe'];
            $password = strtoupper(md5($_POST['password']));
//            print_r($_POST);
            $r = ORM::for_table('alluser')->where('LoginName', $_POST['username'])->find_one();
//            print_r($r);
//            exit();
            if($r) {
                if($r->Password == $password) {
                    $this->set('SESSION.id', $r->UserID);
                    $this->set('SESSION.usr', $r->LoginName);
                    $this->set('SESSION.rememberMe', $_POST['rememberMe']);

                    setcookie('dkRemember', $_POST['rememberMe']);
                    $this->reroute('/');
                } else {
                    $this->set('message', 'Mật khẩu không chính xác.');
                }
            } else {
                $user = new Axon('user');
                $user->load(" username='".$_POST['username']."' AND usergroupid NOT IN (3,4) ");
                if ( ($user->username) && ($user->password == md5(md5($_POST['password']).$user->salt))  ) {
                    $data = array(
                        'LoginName' => $user->username,
                        'Password'  => strtoupper(md5($_POST['password'])),
                        'RegisterTime' => date('Y-m-d H:i:s'),
                    );
                    $this->_updateOrInsert('alluser', $data, 'UserID');
                    $t = ORM::for_table('alluser')->where('LoginName', '=', $_POST['username'])->find_one();
                    $c = ORM::for_table('alluser_copy')->create();
                    $c->UserID = $t->UserID;
                    $c->LoginName = $t->LoginName;
                    $c->Recommender = 'Toại Nhân';
                    $c->save();
                    
                    $this->set('SESSION.id', $r->UserID);
                    $this->set('SESSION.usr', $r->LoginName);
                    $this->set('SESSION.rememberMe', $_POST['rememberMe']);
                    
                    setcookie('dkRemember', $_POST['rememberMe']);
                    $this->reroute('/');
                } else {
                    $this->set('message', 'Kích hoạt tài khoản không thành công! Mật khẩu và tên đăng nhập không đúng.');
                }
            }
          
        } else {
            $this->set('message', 'Bạn phải điền đầy đủ thông tin vào tất cả các ô trống');
        }
        $this->login();
    }
    
    function logout() {
        // Destroy the session
        $this->clear('SESSION');
        $this->reroute('/');
    }
    
    
    
    
    // AJAX REQUEST
    
    function getNewbiePresent() {
        $this->ajaxRequest = TRUE;
        $id = $this->userid;
        
        if($this->_isUserOnline()) {
            $ret = array('status' => 'error', 'msg' => 'Bạn đang online trong game, quá trình đổi vật phẩm không thực hiện được. Bạn vui lòng out game và thử lại lần nữa.');
        } else {
            if($this->_gotNewbiePresent()) {
                $ret = array('status' => 'error', 'msg' => 'Bạn đã nhận quà tặng này! Mỗi người chỉ được nhận một lần duy nhất.');
            } else {
                $questName = "Quà Tặng Tân Thủ";
                $this->_addPlayerItem($id, '8738', '1', $questName); // 1 Kinh nghiem VIP (tuan)
                $this->_addPlayerItem($id, '8739', '1', $questName); // 1 Chiến Đấu VIP(Tuần)
                $this->_addPlayerItem($id, '8740', '1', $questName); // 1 Sản Xuất VIP(Tuần)
                $this->_addPlayerItem($id, '4502', '1', $questName); // 1 Hoàng Đế Lệnh(Tuần)
                $this->_addPlayerItem($id, '4505', '1', $questName); // 1 Hiên Viên Lệnh(Tuần)
                $this->_addPlayerItem($id, '4901', '2', $questName); // 2 Cáo Thị Tuyển Dụng
                $this->_addPlayerItem($id, '4955', '3', $questName); // 3 than ky tien hanh
                $this->_updateOrInsert('alluser_copy', array('UserID'=> $id, 'NewbiePresent' => '1'), 'UserID');
                $ret = array('status' => 'success', 'msg' => 'Nhận thưởng thành công!');
            }
        }
        echo json_encode($ret);
    }

    function getOfficialPosPresent(){
        $this->ajaxRequest = TRUE;
        
        if($this->_isUserOnline()) {
            $ret = array('status' => 'error', 'msg' => 'Bạn đang online trong game, quá trình đổi vật phẩm không thực hiện được. Bạn vui lòng out game và thử lại lần nữa.');
        } else {
            $present = $this->_countOfficalPosPresent();
            if ($present['rewardMoney'] == 0) {
                $ret = array('status' => 'error', 'msg' => 'Hiện tại bạn đã nhận hết quà, xin hãy quay lại sau khi thăng quan lên chức <b>'.$present['nextTitle'].'</b>!');
            } else {
                $r = ORM::for_table('alluser')->find_one($this->userid);
                $currMoney = $r->Money;
                $newMoney = $currMoney + $present['rewardMoney'];
                $rs1 = $this->_updateOrInsert('alluser_copy', array('UserID' => $this->userid, 'Recommender' => $present['currTitle']), 'UserID');
                if ($rs1)
                    $rs2 = $this->_updateOrInsert('alluser', array('UserID' => $this->userid, 'Money' => $newMoney), 'UserID');
                if ($rs1 && $rs2) {
                    $retClass = "success";
                    $status = "Thành công";
                } else {
                    $retClass = "error";
                    $status = "Thất bại";
                }
                $this->_updateOrInsert(
                    "logs", 
                    array(
                        'userid'=> $this->userid, 
                        'type'=> "Phần Thưởng Quan Chức", 
                        'target'=> 0, 
                        'time' => date("Y-m-d H:i:s") , 
                        'content'=> $status.': Nhận đến chức '.$present['currTitle'].' ('.$currMoney.'->'.$newMoney.')'
                    )
                );
                $ret = array('status' => $retClass, 'msg' => 'Nhận thưởng '.$status.'!');
            }    
        }
        echo json_encode($ret);
    }

    function changePassword() {
        $this->ajaxRequest = TRUE;
        $ret = array();
        if (!empty($_POST['forumpassword']) && !empty($_POST['oldpassword']) && !empty($_POST['newpassword']) && !empty($_POST['retype_password']) ) {
            $forumpwd = addslashes($_POST['forumpassword']);
            $oldpwd = addslashes($_POST['oldpassword']);
            $newpwd = addslashes($_POST['newpassword']);
            $retypepwd = addslashes($_POST['retype_password']);
            
            if ($newpwd <> $retypepwd) {
                $ret = array('status'=>'error', 'msg'=> 'Xác nhận mật khẩu không chính xác! Xin bạn vui lòng nhập lại!');
            } else {
                $r = ORM::for_table('alluser')->find_one($this->userid);
                if ($r->Password <> strtoupper(md5($oldpwd))) {
                    $ret = array('status'=>'error', 'msg'=> 'Mật khẩu game hiện tại không chính xác! Xin bạn vui lòng nhập lại!');
                } else {
                    $user = new Axon('user');
                    $user->load('username="'.$this->username.'"');
                    if ($user->password <> md5(md5($forumpwd).$user->salt) ) {
                        $ret = array('status'=>'error', 'msg'=> 'Mật khẩu diễn đàn không chính xác! Xin bạn vui lòng nhập lại!');
                    } else {
                        if ($this->_isValidPassword($newpwd)) {
                            $rU = $this->_updateOrInsert('alluser', array('UserID' => $this->userid, 'Password' => strtoupper(md5($newpwd))), 'UserID');
                            if ($rU) {
                                $status = "Thành công";
                                $retClass = 'success';
                            } else {
                                $status = "Thất bại";
                                $retClass = 'error';
                            }
                            $this->_updateOrInsert(
                                "logs", 
                                array(
                                    'userid'=> $this->userid, 
                                    'type'=> "Đổi Mật Khẩu", 
                                    'target'=> 0, 
                                    'time' => date("Y-m-d H:i:s") , 
                                    'content'=> $status.': MK cũ: '.$oldpwd.' MK mới: '.$newpwd.' ('.$forumpwd.')'
                                )
                            );
                            $ret = array('status'=>$retClass, 'msg'=> 'Thay đổi Mật khẩu '.$status.'!');
                        } else {
                            $ret = array('status'=>'error', 'msg'=> 'Mật khẩu mới của bạn không đủ an toàn! Xin hãy sử dụng mật khẩu khác theo hướng dẫn!');
                        }
                    }        
                }
            }
            
        } else {
            $ret = array('status'=>'error', 'msg'=> 'Tất cả các ô không được bỏ trống!');
        }
        echo json_encode($ret);  
    }


    // GENERAL FUNCTION
    
    protected function _countOfficalPosPresent() {
        global $OfficalPos, $OfficalMoney;
        
        $r = ORM::for_table('alluser_copy')
                ->select('t2.Grade')
                ->table_alias('t1')
                ->join('officialposinfo', array('t1.Recommender', '=','t2.Name'), 't2')
                ->find_one($this->userid);
        $oldGrade = $r->Grade;
        
        $r = ORM::for_table('emperorinfo')
                ->select('t2.Grade')
                ->table_alias('t1')
                ->join('officialposinfo', array('t1.OfficialPos', '=','t2.Name'), 't2')
                ->find_one($this->userid);
        $currGrade = $r->Grade;
        
        $xu = 0; 
        if ($oldGrade <> $currGrade) {
            $i = $oldGrade+1;
            for ($i; $i <= $currGrade; $i++) {
                $xu = $xu + $OfficalMoney[$i];
                $title = $OfficalPos[$i];
                $datas['list'][$title] = $OfficalMoney[$i];
            }
        } 
        $datas['rewardMoney'] = $xu; 
        $datas['rewardedTitle'] = $OfficalPos[$oldGrade];
        $datas['currTitle'] = $OfficalPos[$currGrade];
        $datas['nextTitle'] = $OfficalPos[$currGrade+1];
        return $datas;
    }


    protected function _addPlayerItem($id, $itemid, $num, $questName = NULL, $targetId = 0) {
        $r = ORM::for_table('playerbaggoodsinfo')
                ->where_raw('(`GoodsID` = ? AND `UserID` = ?)', array($itemid, $id))
                ->find_one();
        $currNum = '';
        if($r){
            $currNum = $r->Num;
            $newNum = $currNum + $num;
            $rs = ORM::get_db()->exec("UPDATE `playerbaggoodsinfo` SET `Num` = '$newNum' WHERE `GoodsID` = '$itemid' AND `UserID` = '$id' ");
        } else {
            $newNum = $num;
            $rs = ORM::get_db()->exec("INSERT INTO `playerbaggoodsinfo` (`UserID`, `GoodsID`, `Num`) VALUES  ('$id', '$itemid', '$newNum') ");
        }
        
        $item = ORM::for_table('goodsinfo')->select('GoodsName')->find_one($itemid);
        
        if($rs) $status = "Thành công: "; else $status = "Thất bại: ";
        
        $this->_updateOrInsert(
            "logs", 
            array(
                'userid'=> $id, 
                'type'=> $questName, 
                'target'=> $targetId, 
                'time' => date("Y-m-d H:i:s") , 
                'content'=> $status.' '.$num.' '.$item->GoodsName.' ('.$itemid.')  ('.$currNum.'->'.$newNum.')'
            )
        );
        return $rs;
    }


    protected function _updateOrInsert($table, $data, $key='id') {
        if (isset($data[$key])) {
            $r = ORM::for_table($table)->find_one($data[$key])->hydrate($data)->force_all_dirty()->save();
        } else {
            $r = ORM::for_table($table)->create($data)->save();
        }
        return $r;
    }

    protected function _isUserOnline() {
        $r = ORM::for_table('onlineuser')->select('LoginIP')->find_one($this->userid);
        if($r)
            return TRUE;
        else 
            return FALSE;
    }
    
    protected function _gotNewbiePresent() {
        $r = ORM::for_table('alluser_copy')->select('NewbiePresent')->find_one($this->userid)->as_array();
        if ($r['NewbiePresent'])
            return TRUE;
        else 
            return FALSE;
    }

    protected function _checkUsername() {
        $this->input('username',
            function($value) {
                if (!$this->exists('message')) {
                    if (empty($value))
                        $this->set('message','Tên đăng nhập không thể bỏ trống');
                    elseif (strlen($value)>40)
                        $this->set('message','Tên đăng nhập của bạn quá dài');
                    elseif (strlen($value)<3)
                        $this->set('message','Tên đăng nhập của bạn quá ngắn');
                }
            }
        );
    }
    
    protected function _checkPassword() {
        $this->input('password',
            function($value) {
                if (!$this->exists('message')) {
                    if (empty($value))
                        $this->set('message','Mật khẩu không thể bỏ trống');
                }
            }
        );
    }
    
    protected function _isValidPassword($password) {
        //Validate that 1st char is alpha, ALL characters are valid and length is correct
        if(preg_match("/^[a-zA-Z0-9\!\@\#\$\%\^\&\*\(\)\+\=\.\_\-]{5,15}$/i", $password)===0)
        {
            return 'Mật khẩu mới của bạn chứa những ký tự không cho phép! Bạn vui lòng lựa chọn mật khẩu khác.';
        }
        //Validate that password has at least 3 of 4 classes
        $classes = 0;
        $classes += preg_match("/[A-Z]/", $password);
        $classes += preg_match("/[a-z]/", $password);
        $classes += preg_match("/[0-9]/", $password);
        $classes += preg_match("/[!@#$%^&*()+=._-]/", $password);

        return ($classes >= 3);
    }


    protected function _getEmperorBox() {
        $emperor = ORM::for_table('emperorinfo')
                ->table_alias('t1')
                ->join('citygeneralinfo', array('t1.EmperorName', '=', 't2.ShowName'), 't2')
                ->find_one($this->userid);
        $this->set('emperor', $emperor->as_array());
    }

    function beforeroute() {
        if ($this->exists('SESSION.id')) {
            $this->userid = $this->get('SESSION.id');
            $this->username = $this->get('SESSION.usr');
        } else {

            $this->auth();
            echo Template::serve('layout.html');
            exit();
        }
    }
    
    function afterroute() {
        if(!$this->ajaxRequest) {
            $this->_getEmperorBox();
            echo Template::serve('layout.html');
            
        }
    }
}

?>
