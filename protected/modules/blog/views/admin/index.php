<script language="JavaScript">

    tinyMCE.init({
        mode:"textareas",
        theme:"simple"
    });

    function thread_del(link_id){
        if(window.confirm('Are you sure to delete this thread?')){
            parent.location.href = "<?php echo Yii::app()->params['http_addr'] ?>blog/admin/delThread?id="+link_id;
        }
    }

    function replace_article(link_id, act){
        if(act == 'show'){
            document.getElementById('thread_'+link_id).style.display = 'block';
        }
        if(act == 'cancel'){
            document.getElementById('thread_'+link_id).style.display = 'none';
        }
    }

    function art_del(art_id){
        if(window.confirm('Are you sure to del this article?')){
            parent.location.href = "<?php echo Yii::app()->params['http_addr'] ?>blog/admin/delArticle?id="+art_id;
        }
    }
</script>
<center>
Left menu links
<table border>
    <tr>
        <td>File name</td>
        <td>Alias</td>
        <td>Title</td>

        <td>Delete</td>
        <td>Archive</td>
    </tr>
    <?php foreach($threads as $thread): ?>
    <tr>
        <td><a href="#"><?php echo $thread->blog_articles->link; ?></a></td>
        <td><?php echo $thread->alias; ?></td>
        <td><?php echo $thread->title; ?></td>
        <td><a href="javascript:thread_del('<?php echo $thread->id; ?>')">Del</a></td>
        <td>
            <a href="javascript:replace_article('<?php echo $thread->id; ?>', 'show')">Replace by new</a>
            <div id="thread_<?php echo $thread->id; ?>" style="display: none; position: absolute;">
                <table bgcolor="#aaaaaa" border="0" cellpadding="1" cellspacing="0">
                    <tr>
                        <td>
                            <form action="<?php echo Yii::app()->params['http_addr'] ?>blog/admin/replaceThread" method="POST">
                            <input type="hidden" name="thread_id" value="<?php echo $thread->id; ?>">
                            <table bgcolor="white" border="0" cellpadding="4" cellspacing="0" width="100" height="100">
                                <tbody>
                                    <tr>
                                        <td>New alias</td>
                                        <td align="center" nowrap >
                                            <input type="text" name="new_alias" value="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>New article</td>
                                        <td align="center" nowrap >
                                            <select id="new_art" name="new_art" >
                                                <?php foreach($articles as $article): ?>
                                                <option value="<?php echo $article->id; ?>"><?php echo $article->link; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="submit" value="Replace"></td>
                                        <td><input type="button" value="Cancel" onclick="replace_article('<?php echo $thread->id; ?>', 'cancel')"</td>
                                    </tr>
                                </tbody>
                            </table>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php if((isset($error))&&($error['type'] == 'addThread')): ?>
        <font color="red"><b><?php echo $error['value']; ?></b></font><br><br>
    <?php endif; ?>
<br>Add new link<br>
    <form action="<?php echo Yii::app()->params['http_addr'] ?>blog/admin/addThread" method="POST">
        <table border>
            <tr>
                <td>File name (without ".txt")</td>
                <td>
                    <select id="art_id" name="art_id" >
                        <?php foreach($articles as $article): ?>
                        <option value="<?php echo $article->id; ?>"><?php echo $article->link; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Alias</td>
                <td><input type="text" name="alias" size="10"></td>
            </tr>
            <tr>
                <td>Title</td>

                <td><input type="text" name="title" size="30"></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Add">
                </td>
            </tr>
        </table>
    </form>
</center>
<br>
<center>
<a href="<?php echo Yii::app()->params['http_addr'] ?>blog/admin/usersToApprove">Users to Approve</a>&nbsp;&nbsp;&nbsp;<a href="<?php echo Yii::app()->params['http_addr'] ?>blog/admin/commentsToApprove">Comments to Approve</a>
</center>
<br><br>
<center>
    <?php if((isset($error))&&($error['type'] == 'articlesList')): ?>
        <font color="red"><b><?php echo $error['value']; ?></b></font><br><br>
    <?php endif; ?>
    Articles files list
</center>
<table>
    <tr>
        <td valign="top">
            <table border>
                <tr>
                    <td>File name</td>
                    <td>Delete</td>
                    <td align="center">In page</td>
                </tr>
                <?php foreach($articles as $article): ?>
                <tr>
                    <td>
                        <a href="<?php echo Yii::app()->params['http_addr'].'blog/admin/editArticle?id='.$article->id; ?>"><?php echo $article->link; ?></a>
                    </td>
                    <td>
                        <a href="javascript:art_del('<?php echo $article->id; ?>')">Delete</a>
                    </td>
                    <td align="center">
                        <?php if($article->meta_page == 0): ?>
                        <font color="green"><a href="<?php echo Yii::app()->params['http_addr'].'blog/admin/mainArticle?act=ins&id='.$article->id; ?>">Insert</a></font>
                        <?php else: ?>
                        <font color="red"><a href="<?php echo Yii::app()->params['http_addr'].'blog/admin/mainArticle?act=del&id='.$article->id; ?>">Delete</a></font>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </td>
        <td valign="top">
            <?php if(isset($updatedArt)): ?>
            <table border>
                <tr>
                    <td align="center">
                        <form action="<?php echo Yii::app()->params['http_addr']; ?>blog/admin/updateArticle" method="POST">
                        <input type="hidden" name="art_id" value="<?php echo $updatedArt->id; ?>">
                        <input type="text" name="art_title" value="<?php echo $updatedArt->title; ?>">
                        <textarea cols="50" rows="30" name="art_text">
                        <?php echo $articleText; ?>
                        </textarea><br>
                        <input type="submit" value="Update">
                        </form>
                    </td>
                </tr>
            </table>
            <?php endif; ?>
        </td>
    </tr>
</table>
<center>
<?php if((isset($error))&&($error['type'] == 'addArticle')): ?>
    <font color="red"><b><?php echo $error['value']; ?></b></font><br><br>
<?php endif; ?>
Add new article<br>
<form enctype="multipart/form-data" action="<?php echo Yii::app()->params['http_addr'] ?>blog/admin/addArticle" method="POST">
<table border width="100%">
    <tr>
        <td>Article title</td>
        <td><input type="text" name="new_art_title" value="" size="30"></td>
    </tr>
    <tr>
        <td>Article author</td>

        <td><input type="text" name="new_art_author" value="" size="30"></td>
    </tr>
    <tr>
        <td>Article file</td>
        <td><input type="file" name="new_art_file" value="" size="10">&nbsp;&nbsp;&nbsp;(Only "txt" files)</td>
    </tr>
    <tr>
        <td colspan="2" align="center"><input type="submit" name="new_art" value="Add">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="articleslist_page"> Put this article to "Metaphysical articles" page</td>
    </tr>
</table>
</form>
</center>

