<?php

namespace Core;

class Model
{
    public $id;
    public $pk; // used interchangeably with $id (legacy)
    protected string $table_name;
        protected $limit;
        protected $where = array();
        protected $or_where = array();
        public $output = array();
        public $errors = array();
        private $has_many = array();
        private $belongs_to = array();


    public function __construct()
    {
        $this->db = get_db();
    }

    public function pk(int $id): self
    {
        $this->pk = true;
        $this->id = $id;
        $this->where('id=' . $id);
        return $this;
    }

    public function get(array $options = []): array|object|null
    {
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
            $data = $result->fetch_assoc();
            return $data ? $this->__load($data, true) : null;
        } else {
            $rs = $result->fetch_all(MYSQLI_ASSOC);
            $output = [];
            foreach ($rs as $r) {
                $u = $this->__load($r);
                $output[] = $u;
            }
            return $output;
        }
    }

    public function where(string $statement): self
    {
        $this->where[] = $statement;
        return $this;
    }

    public function or_where(string $statement): self
    {
        $this->or_where = $statement;
        return $this;
    }

    public function limit(int $num, ?int $offset = null): self
    {
        $this->limit = isset($offset) ? "{$offset}, {$num}" : $num;
        return $this;
    }

    public function __call(string $method, array $args): mixed
    {
        if (preg_match("/find_by_(.*)/", $method, $found)) {
            if (in_array($found[1], array_keys($this->fields))) {
                return $this->get_by($found[1], $args[0]);
            }
        }
        return null;
    }

    public function get_by(string $method, string $args): array|object|null
    {
        $this->where($method . '=' . $args);
        return $this->get();
    }

    public function __set(string $name, mixed $value): void
    {
        $this->properties[$name] = $value;
    }

    public function __get(string $name): mixed
    {
        return $this->properties[$name] ?? null;
    }

    public function __load(array $item, ?bool $related = null): self
    {
        $o = new $this();
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

    public function load_related(): void
    {
        $this->load_has_many();
        $this->load_belongs_to();
    }

    public function belongs_to(string $entity, ?string $call_name = null): void
    {
        $data = [];
        $data['class'] = $entity;
        $data['call_name'] = empty($call_name) ? strtolower($entity) : $call_name;

        $this->belongs_to[$data['call_name']] = $data['class'];
    }

    public function has_many(string $entity, ?string $call_name = null): void
    {
        $data = [];
        $data['class'] = $entity;
        $data['call_name'] = empty($call_name) ? strtolower($entity) : $call_name;

        $this->has_many[$data['call_name']] = $data['class'];
    }

    public function load_has_many(): bool
    {
        if (empty($this->has_many)) return false;
        foreach ($this->has_many as $call_name => $entity) {
            $e = new $entity;
            $this->$call_name = $e->{"find_by_" . $this->type . '_id'}($this->id);
        }
        return true;
    }

    public function load_belongs_to(): bool
    {
        if (empty($this->belongs_to)) return false;
        foreach ($this->belongs_to as $call_name => $entity) {
            $e = new $entity;
            $e->where('id=' . $this->{$call_name . '_id'});
            $x = $e->get();
            $this->$call_name = $x[0] ?? null;
        }
        return true;
    }

    public function form(): string
    {
        return form($this);
    }

    public function create(array $options): mixed
    {
        return $this->db->insert(array_keys($options), array_values($options), $this->table_name);
    }

    public function update(array $options): mixed
    {
        return $this->db->update(array_keys($options), array_values($options), $this->table_name, 'where id=' . $this->id);
    }

    public function delete(): mixed
    {
        return $this->db->noQuery('DELETE FROM ' . $this->table_name . ' WHERE id=' . $this->id);
    }

    public function validate(array $options): bool
    {
        foreach ($this->fields as $k => $field) {
            $rules = $field['validation'];

            if (!empty($rules)) {
                foreach ($rules as $rule) {
                    if (is_valid($rule, $options[$k])) {
                        continue;
                    } else {
                        $this->errors[] = [
                            'rule' => $rule,
                            'field' => $k,
                        ];
                    }
                }
            }
        }
        return empty($this->errors);
    }

    public function save(array $data): mixed
    {
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
