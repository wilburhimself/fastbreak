<?php
    class Model {
        public $id;
        public $pk; // used interchangeably with $id (legacy)
        protected $table_name;
        public $options;
        public $properties = array();
        protected $limit;
        protected $where = array();
        protected $or_where = array();
        public $output = array();
        public $errors = array();
        private $has_many = array();
        private $belongs_to = array();

    
        function __construct() {
            $this->db = get_db();
        }
    
        function pk($id) {
            $this->pk = true;
            $this->id = $id;
            $this->where('id='.$id);
            return $this;
        }
        
        public function get($options=array()) {
            $sql['from'] = $this->table_name;
            if (!empty($this->where)) {
                $sql['where'] = join(' AND ', $this->where);
            }
            if (!empty($this->or_where)) {
                $sql['or_where'] = join(' OR ', $this->where);
            }
            if (!empty($this->limit)) {
                $sql['limit'] = $this->limit;
            }
            $sql = array_merge($sql, $options);
            $result = $this->db->select($sql);
            
            if ($this->pk == true) {
                return $this->__load($result->fetch(), $related=TRUE);
            } else {
                $rs = $result->fetch_array();
                foreach ($rs as $r) {
                    $u = $this->__load($r);
                    $this->output[] = $u;
                }
                return $this->output;   
            }
        }
        
        public function where($statement) {
            $this->where[] = $statement;
            return $this;
        }

        public function or_where($statement) {
            $this->or_where = $statement;
            return $this;
        }

        public function limit($num, $offset=null) {
            $this->limit = isset($offset) ? "{$offset}, {$num}" : $num;
            return $this;
        }

        public function __call($method, $args) {
            if (preg_match( "/find_by_(.*)/", $method, $found)) {
                if (in_array($found[1], array_keys($this->fields))) {
                    return $this->get_by($found[1], $args[0]);
                }
            }
        }

        public function get_by($method, $args) {
            $this->where($method.'='.$args);
            return $this->get();
        }
        
        public function __set($name, $value) {
            $this->properties[$name] = $value;
        }
        
        public function __get($name) {
            if (is_string($this->properties[$name])) {
                return htmlentities($this->properties[$name]);
            }
            return $this->properties[$name];
        }
        
        public function __load($item, $related=null) {
            $o = new $this($item);

            $o->id = $item['id'];
            foreach ($item as $k => $v) {
                $o->$k = $v;
            }
            if ($related) {
                $o->load_has_many();
                $o->load_belongs_to();
            }

            return $o;
        }

        public function load_related() {
            $this->load_has_many();
            $this->load_belongs_to();
            
        }

         public function belongs_to($entity, $call_name = null) {
            $data = array();
            $data['class'] = $entity;
            $data['call_name'] = empty($call_name) ? strtolower($entity) : $call_name;

            $this->belongs_to[$data['call_name']] = $data['class'];
        }

        public function has_many($entity, $call_name = null) {
            $data = array();
            $data['class'] = $entity;
            $data['call_name'] = empty($call_name) ? strtolower($entity) : $call_name;

            $this->has_many[$data['call_name']] = $data['class'];
        }

        public function load_has_many() {
            if (empty($this->has_many)) return false;
            foreach ($this->has_many as $call_name => $entity) {
                $e = new $entity;
                $this->$call_name = $e->{"find_by_".$this->type.'_id'}($this->id);
            }
        }

        public function load_belongs_to() {
            if (empty($this->belongs_to)) return false;
            foreach ($this->belongs_to as $call_name => $entity) {
                $e = new $entity;
                $e->where('id='.$this->{$call_name.'_id'});
                $x = $e->get();
                $this->$call_name = $x[0];
            }
        }
        public function form() {
            return form($this);
        }
        
        public function create($options) {
            return $this->db->insert(array_keys($options), array_values($options), $this->table_name);
        }
        
        public function update($options) {
            return $this->db->update(array_keys($options), array_values($options), $this->table_name, 'where id='.$this->id);
        }
        
        public function delete() {
            return $this->db->noQuery('DELETE FROM '.$this->table_name.' WHERE id='.$this->id);
        }
        
        public function validate($options) {
            foreach ($this->fields as $k => $field) {
                $rules = $field['validation'];
                
                if (!empty($rules)) {
                    foreach ($rules as $rule) {
                        if (is_valid($rule, $options[$k])) {
                            continue;
                        } else {
                            $this->errors[] = array(
                                'rule' => $rule,
                                'field' => $k,
                            );
                        }
                    }
                }
            }
            if (!empty($this->errors)) {
                return false;
            } else {
                return true;
            }
        }

        public function save($data) {
            if (!empty($data['id'])) {
                if (property_exists($this, 'updated_at')) {
                    $data['updated_at'] = time();
                }
                $this->id = $data['id'];
                unset($data['id']);
                return $this->update($data);
            } else {
                $data['created_at'] = time();
                return $this->create($data);
            }
        }
        
    }
?>
