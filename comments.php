<?php
require_once 'includes/config.php';
$pageTitle = 'Community Discussion - COVID-19 Portal';
$error = ''; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $comment  = sanitize($_POST['comment'] ?? '');
    $category = sanitize($_POST['category'] ?? 'general');
    $allowedCats = ['general','prevention','symptoms','vaccine','treatment'];
    if (!preg_match('/^[\s\S]{10,1000}$/', $comment)) $error = 'Comment must be 10-1000 characters.';
    elseif (!in_array($category, $allowedCats))       $error = 'Invalid category.';
    else {
        $pdo->prepare("INSERT INTO comments (user_id,comment,category) VALUES(?,?,?)")
            ->execute([$_SESSION['user_id'], $comment, $category]);
        $success = 'Comment posted successfully!';
    }
}
if (isset($_GET['like']) && isLoggedIn()) {
    $pdo->prepare("UPDATE comments SET likes=likes+1 WHERE id=?")->execute([(int)$_GET['like']]);
    echo json_encode(['success'=>true]); exit;
}
if (isset($_GET['delete']) && isLoggedIn()) {
    $pdo->prepare("DELETE FROM comments WHERE id=? AND user_id=?")->execute([(int)$_GET['delete'], $_SESSION['user_id']]);
    header("Location: comments.php"); exit;
}
$catFilter = isset($_GET['cat']) && in_array($_GET['cat'],['general','prevention','symptoms','vaccine','treatment']) ? $_GET['cat'] : '';
$page = max(1,(int)($_GET['page']??1)); $perPage = 10; $offset = ($page-1)*$perPage;
$where = $catFilter ? "WHERE c.category=?" : ''; $params = $catFilter ? [$catFilter] : [];
$total = $pdo->prepare("SELECT COUNT(*) FROM comments c $where"); $total->execute($params); $totalComments = $total->fetchColumn();
$totalPages = ceil($totalComments/$perPage);
$stmt = $pdo->prepare("SELECT c.*,u.username,u.full_name FROM comments c JOIN users u ON c.user_id=u.id $where ORDER BY c.created_at DESC LIMIT $perPage OFFSET $offset");
$stmt->execute($params); $comments = $stmt->fetchAll();
$categories = ['general'=>'&#127758;','prevention'=>'&#128737;','symptoms'=>'&#129310;','vaccine'=>'&#128137;','treatment'=>'&#128138;'];
include 'includes/header.php';
?>
<div class="container my-5">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-lg-3 mb-4">
      <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-header bg-primary text-white fw-semibold"><i class="fas fa-filter me-2"></i>Filter by Category</div>
        <div class="list-group list-group-flush">
          <a href="comments.php" class="list-group-item list-group-item-action <?= !$catFilter?'active':'' ?>">&#127758; All <span class="badge bg-secondary float-end"><?= $totalComments ?></span></a>
          <?php foreach($categories as $cat=>$icon): ?>
          <a href="comments.php?cat=<?= $cat ?>" class="list-group-item list-group-item-action <?= $catFilter===$cat?'active':'' ?>"><?= $icon ?> <?= ucfirst($cat) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="card border-0 shadow-sm rounded-3"><div class="card-body text-center"><h2 class="fw-bold text-primary"><?= $totalComments ?></h2><p class="mb-0 text-muted">Total Discussions</p></div></div>
    </div>
    <!-- Main -->
    <div class="col-lg-9">
      <h4 class="fw-bold mb-4"><i class="fas fa-comments me-2 text-primary"></i>Community Discussion</h4>
      <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
      <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

      <?php if(isLoggedIn()): ?>
      <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-light fw-semibold"><i class="fas fa-pen me-2"></i>Share Your Thoughts</div>
        <div class="card-body">
          <form method="POST" id="commentForm">
            <div class="mb-3">
              <textarea name="comment" id="commentText" class="form-control" rows="3" placeholder="Share your experience or ask a question... (min 10 chars)" required></textarea>
              <div class="d-flex justify-content-between mt-1">
                <small class="text-muted">Min 10 - Max 1000 characters</small>
                <small class="text-muted"><span id="charCount">0</span>/1000</small>
              </div>
            </div>
            <div class="d-flex gap-2 align-items-center">
              <select name="category" class="form-select w-auto">
                <?php foreach($categories as $cat=>$icon): ?><option value="<?= $cat ?>"><?= $icon ?> <?= ucfirst($cat) ?></option><?php endforeach; ?>
              </select>
              <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i>Post</button>
            </div>
          </form>
        </div>
      </div>
      <?php else: ?>
      <div class="alert alert-info"><i class="fas fa-info-circle me-2"></i><a href="login.php" class="fw-semibold">Login</a> or <a href="register.php" class="fw-semibold">Register</a> to join the discussion.</div>
      <?php endif; ?>

      <?php if(empty($comments)): ?><div class="text-center py-5"><i class="fas fa-comment-slash fs-1 text-muted mb-3"></i><p class="text-muted">No comments yet.</p></div><?php endif; ?>

      <?php foreach($comments as $c): ?>
      <div class="card border-0 shadow-sm mb-3 comment-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div class="d-flex align-items-center gap-2 mb-2">
              <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px;flex-shrink:0;"><?= strtoupper(substr($c['username'],0,1)) ?></div>
              <div><strong><?= htmlspecialchars($c['full_name']) ?></strong> <small class="text-muted">@<?= htmlspecialchars($c['username']) ?></small><br><small class="text-muted"><?= date('d M Y, h:i A',strtotime($c['created_at'])) ?></small></div>
            </div>
            <span class="badge bg-primary text-capitalize"><?= $categories[$c['category']] ?> <?= $c['category'] ?></span>
          </div>
          <p class="mb-2"><?= nl2br(htmlspecialchars($c['comment'])) ?></p>
          <div class="d-flex gap-3">
            <button class="btn btn-sm btn-outline-primary like-btn" data-id="<?= $c['id'] ?>"><i class="fas fa-heart me-1"></i><span class="like-count"><?= $c['likes'] ?></span></button>
            <?php if(isLoggedIn()&&$_SESSION['user_id']==$c['user_id']): ?>
            <a href="comments.php?delete=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <?php if($totalPages>1): ?>
      <nav class="mt-4"><ul class="pagination justify-content-center">
        <?php for($i=1;$i<=$totalPages;$i++): ?><li class="page-item <?= $i===$page?'active':'' ?>"><a class="page-link" href="?page=<?= $i ?><?= $catFilter?'&cat='.$catFilter:'' ?>"><?= $i ?></a></li><?php endfor; ?>
      </ul></nav>
      <?php endif; ?>
    </div>
  </div>
</div>
<script>
document.getElementById('commentText')?.addEventListener('input',function(){
    document.getElementById('charCount').textContent=this.value.length;
    const v=this.value.length>=10&&this.value.length<=1000;
    this.classList.toggle('is-invalid',!v&&this.value.length>0);
    this.classList.toggle('is-valid',v);
});
document.getElementById('commentForm')?.addEventListener('submit',function(e){
    if(!/^[\s\S]{10,1000}$/.test(document.getElementById('commentText').value)){
        e.preventDefault(); document.getElementById('commentText').classList.add('is-invalid');
    }
});
document.querySelectorAll('.like-btn').forEach(btn=>{
    btn.addEventListener('click',function(){
        fetch(`comments.php?like=${this.dataset.id}`).then(r=>r.json()).then(d=>{
            if(d.success){const c=this.querySelector('.like-count');c.textContent=parseInt(c.textContent)+1;this.classList.replace('btn-outline-primary','btn-primary');this.disabled=true;}
        });
    });
});
</script>
<?php include 'includes/footer.php'; ?>
