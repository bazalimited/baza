<?php

class Category extends CI_Model {
    /*
      Determines if a given category id exists
     */

    function exists($category_id) {
        $this->db->from('categories');
        $this->db->where('id', $category_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function get_all_categories_and_sub_categories_as_tree($location_id = NULL) {
        $categories = $this->get_all_categories_and_sub_categories(NULL, 0, $location_id);

        $objects = array();
        // turn to array of objects to make sure our elements are passed by reference
        foreach ($categories as $k => $v) {
            $node = new StdClass();
            $node->id = $k;
            $node->parent_id = $v['parent_id'];
            $node->name = $v['name'];
            $node->owner_payment_amount = $v['owner_payment_amount'];
            $node->agent_payment_amount = $v['agent_payment_amount'];
            $node->record_sn = $v['record_sn'];
            $node->hide_from_grid = $v['hide_from_grid'];
            $node->depth = $v['depth'];
            $node->children = array();
            $objects[$k] = $node;
        }


        // list dependencies parent -> children
        foreach ($objects as $node) {
            $parent_id = $node->parent_id;
            if ($parent_id !== null) {
                $objects[$parent_id]->children[] = $node;
            }
        }
        return array_filter($objects, array('Category', '_filter_to_root'));
    }

    function get_all_categories_and_sub_categories_as_indexed_by_name_key() {
        $categories = $this->sort_categories_and_sub_categories($this->get_all_categories_and_sub_categories());

        foreach ($categories as $index => $cat) {
            if (!isset($categories[$index]['key'])) {
                $categories[$index]['key'] = $cat['name'] . '|';
            } else {
                $categories[$index]['key'] .= $cat['name'] . '|' . $categories[$index]['key'];
            }

            if ($cat['parent_id']) {
                $this->key_categories($categories, $index, $cat['parent_id']);
            }
        }

        $indexed_categories = array();

        foreach ($categories as $category_id => $category) {
            $indexed_categories[strtoupper(rtrim($category['key'], '|'))] = $category_id;
        }

        return $indexed_categories;
    }

    function get_all_categories_and_sub_categories_as_indexed_by_category_id() {
        $categories = $this->sort_categories_and_sub_categories($this->get_all_categories_and_sub_categories());

        foreach ($categories as $index => $cat) {
            if (!isset($categories[$index]['key'])) {
                $categories[$index]['key'] = $cat['name'] . '|';
            } else {
                $categories[$index]['key'] .= $cat['name'] . '|' . $categories[$index]['key'];
            }

            if ($cat['parent_id']) {
                $this->key_categories($categories, $index, $cat['parent_id']);
            }
        }

        $indexed_categories = array();

        foreach ($categories as $category_id => $category) {
            $indexed_categories[$category_id] = rtrim($category['key'], '|');
        }

        return $indexed_categories;
    }

    function key_categories(&$categories, $cur_cat_index, $parent_id) {
        $parent_category = $categories[$parent_id];

        $categories[$cur_cat_index]['key'] = $parent_category['name'] . '|' . $categories[$cur_cat_index]['key'];

        if ($parent_category['parent_id']) {
            $this->key_categories($categories, $cur_cat_index, $parent_category['parent_id']);
        }
    }

    function get_all_categories_and_sub_categories($parent_id = NULL, $depth = 0, $location_id = NULL) {

        $categories = $this->get_all($parent_id, TRUE, 10000, 0, 'name', 'asc', $location_id);
        if (!empty($categories)) {
            foreach ($categories as $id => $value) {
                $categories[$id]['depth'] = $depth;
            }

            foreach (array_keys($categories) as $id) {
                $subcategories = $this->get_all_categories_and_sub_categories($id, $depth + 1);

                if (!empty($subcategories)) {
                    $this->load->helper('array');
                    $categories = array_replace($categories, $subcategories);
                }
            }

            return $categories;
        } else {
            return $categories;
        }
    }

    function sort_categories_and_sub_categories($categories) {
        $objects = array();
        // turn to array of objects to make sure our elements are passed by reference
        foreach ($categories as $k => $v) {
            $node = new StdClass();
            $node->id = $k;
            $node->parent_id = $v['parent_id'];
            $node->name = $v['name'];
            $node->hide_from_grid = $v['hide_from_grid'];
            $node->depth = $v['depth'];
            $node->children = array();
            $objects[$k] = $node;
        }


        // list dependencies parent -> children
        foreach ($objects as $node) {
            $parent_id = $node->parent_id;
            if ($parent_id !== null) {
                $objects[$parent_id]->children[] = $node;
            }
        }

        // clean the object list to make kind of a tree (we keep only root elements)
        $sorted = array_filter($objects, array('Category', '_filter_to_root'));

        // flatten recursively
        $categories = self::_flatten($sorted);

        $return = array();

        foreach ($categories as $category) {
            $return[$category->id] = array('depth' => $category->depth, 'name' => $category->name, 'hide_from_grid' => $category->hide_from_grid, 'parent_id' => $category->parent_id);
        }

        return $return;
    }

    static function _filter_to_root($node) {
        return $node->depth === 0;
    }

    static function _flatten($elements) {
        $result = array();

        foreach ($elements as $element) {
            if (property_exists($element, 'children')) {
                $children = $element->children;
                unset($element->children);
            } else {
                $children = null;
            }

            $result[] = $element;

            if (isset($children)) {
                $flatened = self::_flatten($children);

                if (!empty($flatened)) {
                    $result = array_merge($result, $flatened);
                }
            }
        }
        return $result;
    }

    function get_all($parent_id = NULL, $show_hidden = FALSE, $limit = 10000, $offset = 0, $col = 'name', $order = 'asc', $location_id = NULL) {
        static $cache = array();
        if (!$cache) {
            $this->db->from('categories');
            $this->db->where('deleted', 0);

            if (!$show_hidden) {
                $this->db->where('hide_from_grid', 0);
            }

            $this->db->order_by($col, $order);

            foreach ($this->db->get()->result_array() as $result) {
                $cache[$result['parent_id'] ? $result['parent_id'] : 0][] = array('name' => $result['name'], 'owner_payment_amount' => $result['owner_payment_amount'], 'agent_payment_amount' => $result['agent_payment_amount'], 'record_sn' => $result['record_sn'], 'hide_from_grid' => $result['hide_from_grid'], 'parent_id' => $result['parent_id'], 'id' => $result['id']);
            }
        }

        $return = array();

        $key = $parent_id == NULL ? 0 : $parent_id;
        if (isset($cache[$key])) {
            foreach ($cache[$key] as $row) {
                $return[$row['id']] = array('name' => $row['name'], 'owner_payment_amount' => $row['owner_payment_amount'], 'agent_payment_amount' => $row['agent_payment_amount'], 'record_sn' => $row['record_sn'], 'hide_from_grid' => $row['hide_from_grid'], 'parent_id' => $row['parent_id'], 'depth' => NULL);
            }
        }
        return array_slice($return, $offset, $limit, TRUE);
    }

    function get_search_suggestions($search) {
        if (!trim($search)) {
            return array();
        }

        $suggestions = array();
        $this->db->select('name, id');
        $this->db->from('categories');
        $this->db->where('deleted', 0);
        if ($this->config->item('supports_full_text') && !$this->config->item('legacy_search_method')) {
            $this->db->where("MATCH (name) AGAINST (" . $this->db->escape(escape_full_text_boolean_search($search) . '*') . " IN BOOLEAN MODE)", NULL, FALSE);
        } else {
            $this->db->like('name', $search);
        }

        $this->db->limit(25);
        $by_category = $this->db->get();
        foreach ($by_category->result() as $row) {
            $suggestions[] = array('id' => $row->id, 'label' => $row->name);
        }

        return $suggestions;
    }

    /*
      Gets information about a particular category
     */

    function get_info($category_id) {
        $this->db->from('categories');
        $this->db->where('id', $category_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get empty base parent object, as $item_id is NOT an item
            $item_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('categories');

            foreach ($fields as $field) {
                $item_obj->$field = '';
            }

            return $item_obj;
        }
    }

    function category_name_exists($category_name) {
        
        $this->db->from('categories');
        $this->db->where('name', $category_name);

        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return true;
        } else {
            return false;
        }
    }
   

    /*
      Gets information about multiple categories
     */

    function get_multiple_info($category_ids) {
        $this->db->from('categories');
        $this->db->where_in('id', $category_ids);
        $this->db->order_by("name", "asc");
        return $this->db->get();
    }

    function count_all($parent_id = NULL, $show_hidden = FALSE) {
        $this->db->from('categories');
        $this->db->where('deleted', 0);

        if (!$show_hidden) {
            $this->db->where('hide_from_grid', 0);
        }

        if ($parent_id === NULL) {
            $this->db->where('parent_id IS NULL', null, false);
        } else if ($parent_id) {
            $this->db->where('parent_id', $parent_id);
        }
        return $this->db->count_all_results();
    }

    function get_category_id($name) {
        $categories = $this->get_all_categories_and_sub_categories_as_indexed_by_name_key();
        $name = strtoupper($name);
        return isset($categories[$name]) ? $categories[$name] : NULL;
    }

    function create_categories_as_needed($category_name, &$categories_indexed_by_name) {
        $category_list = explode('|', $category_name);

        for ($k = 0; $k < count($category_list); $k++) {
            $category = $category_list[$k];
            $category_string = implode('|', array_slice($category_list, 0, $k + 1));

            if (!isset($categories_indexed_by_name[strtoupper($category_string)])) {
                $parent_category_search = substr($category_string, 0, strrpos($category_string, '|') === FALSE ? NULL : strrpos($category_string, '|'));
                $parent_id = isset($categories_indexed_by_name[strtoupper($parent_category_search)]) ? $categories_indexed_by_name[strtoupper($parent_category_search)] : NULL;
                $categories_indexed_by_name[strtoupper($category_string)] = $this->save($category, NULL, $parent_id);
            }
        }
    }

    function save($category_data = array(), $category_id = false) {
        if ($category_id == FALSE) {
            if ($category_data) {
                if ($this->db->insert('categories', $category_data)) {
                    return $this->db->insert_id();
                }
            }
            return FALSE;
        } else {
            $this->db->where('id', $category_id);


            if ($this->db->update('categories', $category_data)) {
                return $category_id;
            }
        }
        return FALSE;
    }

    /*
      Deletes one category
     */

    function delete($category_id) {
        $this->db->where('id', $category_id);
        return $this->db->update('categories', array('deleted' => 1));
    }

}
