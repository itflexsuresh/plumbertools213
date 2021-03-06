
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8">
				<div class="card">
					<div class="header">
					<?php if ($action == "new") { ?>
						<h4 class="title"><?php echo $toolboxtalksname['content']; ?> Content Manager</h4>
					<?php } if ($action == "edit") { ?>
						<h4 class="title"><?php echo $toolboxtalksname['content']; ?> Update Content Manager</h4>
					<?php } ?>
					</div>
					<div class="content">
						<form method="post" action="<?php echo base_url();?>admincontrol/toolboxtalkssection1action/<?php echo $toolboxtalksid; ?>"  enctype="multipart/form-data">							
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">	
										<input name="toolboxtalksid" type="hidden" class="form-control" placeholder="Product Guides" value="<?php echo $toolboxtalksid; ?>">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Content</label>
									<?php if ($action == "new") { ?>										
										<input name="content" type="text" class="form-control" placeholder="Content" value="<?php echo set_value('content'); ?>">
									<?php } if ($action == "edit") { ?>
										<input name="content" type="text" class="form-control" placeholder="Content" value="<?php echo set_value('content',$getdata['content']); ?>">
									<?php } ?>
										<span style='color: red'><?php echo form_error('content'); ?></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Description</label>
									<?php if ($action == "new") { ?>
										<textarea name="description" rows="5" class="form-control" placeholder="Description" value=""><?php echo set_value('description'); ?></textarea>
									<?php } if ($action == "edit") { ?>
										<textarea name="description" rows="5" class="form-control" placeholder="Description"><?php echo set_value('description',$getdata['description']); ?></textarea>
									<?php } ?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Detailed Description</label>
									<?php if ($action == "new") { ?>
										<textarea name="detaildescription" rows="5" class="form-control" placeholder="Detailed Description" value=""><?php echo set_value('detaildescription'); ?></textarea>
									<?php } if ($action == "edit") { ?>
										<textarea name="detaildescription" rows="5" class="form-control" placeholder="Detailed Description"><?php echo set_value('detaildescription',$getdata['detaildescription']); ?></textarea>
									<?php } ?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Link</label>
									<?php if ($action == "new") { ?>
										<input name="link" type="text" class="form-control" placeholder="Link" value="<?php echo set_value('link'); ?>">
									<?php } if ($action == "edit") { ?>
										<input name="link" type="text" class="form-control" placeholder="Link" value="<?php echo set_value('link',$getdata['link']);?>">
									<?php } ?>
									<span style='color: red'><?php echo form_error('link'); ?></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Image</label>
									<?php if ($action == "new") { ?>
										<input name="imagefile" type="file" class="form-control" placeholder="Image" value="<?php echo set_value('imagefile'); ?>">
									<?php } if ($action == "edit") { ?>
										<img src="<?php echo base_url();?>./images/<?php echo $getdata['image'];?>" height="50" width="50">
										<input name="imagefile" type="file" class="form-control" placeholder="Image" value="<?php echo set_value('imagefile',$getdata['image']); ?>">
									<?php } ?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Position</label>
									<?php if ($action == "new") { ?>
										<input name="position" type="text" class="form-control" placeholder="Position" value="<?php echo set_value('position'); ?>">
									<?php } if ($action == "edit") { ?>
										<input name="position" type="text" class="form-control" placeholder="Position" value="<?php echo set_value('position',$getdata['position']);?>">
									<?php } ?>
									<span style='color: red'><?php echo form_error('position'); ?></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Published</label>
									<?php if ($action == "new") { ?>
										<select name="publishid" id="publishid">
											<option value="1" <?php echo set_select('publishid', '1'); ?>>True</option>
											<option value="0" <?php echo set_select('publishid', '0'); ?>>False</option>
										</select>
									<?php } if ($action == "edit") { ?>
										<select name="publishid" id="publishid">											
											<option value="1" <?php echo set_select('publishid', '1'); if($getdata['published'] == "1"){ ?> selected <?php } ?> >True</option>											
											<option value="0" <?php echo set_select('publishid', '0'); if($getdata['published'] == "0"){ ?> selected<?php } ?> >False</option>											
										</select>
									<?php } ?>
									</div>
								</div>
							</div>
							
						<?php if ($action == "new") { ?>
							<input name="insert" type="hidden" value="1">
							<button type="submit" class="btn btn-info btn-fill pull-left">Save</button>						
						<?php } if ($action == "edit") { ?>
							<input name="update" type="hidden" value="1">
							<input name="updateid" type="hidden" value="<?php echo $getdata['id']; ?>">
							<button type="submit" class="btn btn-info btn-fill pull-left">Update</button>
						<?php } ?>
							
							<a href="<?php echo base_url();?>admincontrol/toolboxtalkssection1action/<?php echo $toolboxtalksid; ?>"><button type="button" class="btn btn-warning btn-fill pull-left">Back</button></a>
							<div class="clearfix"></div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
