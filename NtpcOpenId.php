<?php
require_once "openid.php";
class NtpcOpenId extends LightOpenId
{
    private $ntpcData = []; // Ntpc OpenID
    private $allowed_school_ids = []; //允許的學校代號

    public function __construct($host)
    {
        parent::__construct($host);
        $this->identity = "https://openid.ntpc.edu.tw";
        $this->setRequried();
    }

    /**
     * 解析 Ntpc Data
     *
     * @return void
     */
    public function getNtpcAttributes()
    {
        $attr = $this->getAttributes();
        $tmp = explode('/', $this->identity);
        $ntpcAttr['account'] = end($tmp); // 帳號

        foreach($this->required as $field_name => $field_value){

            switch($field_name){
                case 'class_info':
                    $ntpcAttr['grade'] = substr($attr['pref/language'], 0, 2); // 年級
                    $ntpcAttr['class'] = substr($attr['pref/language'], 2, 2); // 班級
                    $ntpcAttr['class_no'] = substr($attr['pref/language'], 4, 2); // 座號
                break;
                case 'permission_info':
                    foreach (json_decode($attr['pref/timezone']) as $item) {
                        $ntpcAttr['workplaces'][$item->id]['school_title'] =
                            $item->name; // 單位全銜
                        $ntpcAttr['workplaces'][$item->id]['role'] = $item->role; // 身分別
                        $ntpcAttr['workplaces'][$item->id]['title'] = $item->title; // 職稱別
                        $ntpcAttr['workplaces'][$item->id]['groups'] = $item->groups; // 職務別
                        $ntpcAttr['workplaces'][$item->id]['groups_string'] =
                            $item->groups; // 職務別
                    }
                break;
                default:
                $ntpcAttr[$field_name] = $field_value;
            }
        }

        return $ntpcAttr;
    }

    /**
     * 驗證
     *
     * @return boolean
     */
    public function validate()
    {
        $ret = parent::validate();
        if ($ret) {
            $this->ntpcData = $this->getNtpcAttributes();
        }
        return $ret;
    }

    /**
     * 傳回Ntpc OpenID Data
     *
     * @return array
     */
    public function getNtpcData()
    {
        return $this->ntpcData;
    }

    /**
     * 是否具備身份
     *
     * @param string $role
     * @return boolean
     */
    public function hasRole($role)
    {
        $ret = false;
        if (count($this->allowed_school_ids)) {
            foreach (
                $this->ntpcData['workplaces']
                as $school_id => $workplace
            ) {
                foreach ($this->allowed_school_ids as $allow_school_id) {
                    if (
                        $school_id == $allow_school_id and
                        $workplace['role'] == $role
                    ) {
                        $ret = true;
                    }
                }
            }
        } else {
            foreach (
                $this->ntpcData['workplaces']
                as $school_id => $workplace
            ) {
                if ($workplace['role'] == $role) {
                    $ret = true;
                }
            }
        }
        return $ret;
    }

    /**
     * 是否具備職稱
     *
     * @param string $title
     * @return boolean
     */
    public function hasTitle($title)
    {
        $ret = false;
        if (count($this->allowed_school_ids)) {
            foreach (
                $this->ntpcData['workplaces']
                as $school_id => $workplace
            ) {
                foreach ($this->allowed_school_ids as $allow_school_id) {
                    if (
                        $school_id == $allow_school_id and
                        $title == $workplace['title']
                    ) {
                        $ret = true;
                    }
                }
            }
        } else {
            foreach (
                $this->ntpcData['workplaces']
                as $school_id => $workplace
            ) {
                if ($title == $workplace['title']) {
                    $ret = true;
                }
            }
        }
        return $ret;
    }

    /**
     * 是否具備職務
     *
     * @param string $group
     * @return boolean
     */
    public function hasGroup($group)
    {
        $ret = false;
        if (count($this->allowed_school_ids)) {
            foreach (
                $this->ntpcData['workplaces']
                as $school_id => $workplace
            ) {
                foreach ($this->allowed_school_ids as $allow_school_id) {
                    if (
                        $school_id == $allow_school_id and
                        in_array($group, $workplace['groups'])
                    ) {
                        $ret = true;
                    }
                }
            }
        } else {
            foreach (
                $this->ntpcData['workplaces']
                as $school_id => $workplace
            ) {
                if (in_array($group, $workplace['groups'])) {
                    $ret = true;
                }
            }
        }
        return $ret;
    }

    public function setAllowedSchool($Ids)
    {
        if (gettype($Ids) == "array") {
            $this->allowed_school_ids = $Ids;
        } elseif (gettype($Ids) == "string") {
            $this->allowed_school_ids = [$Ids];
        }
    }

    public function setRequried($required_type = 0)
    {
        if ($required_type) {
            $this->required = [
                'nickname' => 'namePerson/friendly',
                'email' => 'contact/email',
                'cname' => 'namePerson',
                'birth' => 'birthDate',
                'gender' => 'person/gender',
                'identity_code' => 'contact/postalCode/home',
                'school_title' => 'contact/country/home',
                'class_info' => 'pref/language',
                'permission_info' => 'pref/timezone',
            ];
        } else {
            $this->required = [
                'email' => 'contact/email',
                'cname' => 'namePerson',
                'school_title' => 'contact/country/home',
                'class_info' => 'pref/language',
                'permission_info' => 'pref/timezone',
                ];
        }
    }
}