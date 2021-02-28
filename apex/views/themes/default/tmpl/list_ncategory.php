<?php
$this->db->where('banned', 0);
$this->db->order_by('sort', 'asc');
$q = $this->db->get('ncategory');
$res = $q->result_array();

$uri = uri(3);

if(!empty($res)): ?>

<ul class="list-category">
	<?php foreach($res as $r): ?>
	<li><a href="/news/cat/<?php echo $r['link'] ?>" class="<?php if($uri == $r['link']) echo 'nactive' ?>" style="font-size: 14px; text-transform: uppercase; font-family: 'Geometria-Bold'; color: #000; letter-spacing: 1px;"><?php echo $r['name'] ?></a></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>