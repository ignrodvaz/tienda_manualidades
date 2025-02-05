														<!-- MODAL CREAR CATEGORIA -->
														<!-- begin::Modal body -->
														<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
															<?php if (isset($validation)): ?>
																<div class="alert alert-danger">
																	<?= $validation->listErrors() ?>
																</div>
															<?php endif; ?>
															<!--begin::Form-->
															<form action="<?= isset($categoria) ? base_url('categoria/save/') . $categoria['PK_ID_CATEGORIA'] : base_url('categoria/save') ?>" id="kt_modal_add_user_form" class="form" method="post">
																<!--begin::Scroll-->
																<div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
																	<!--begin::Input group-->
																	<div class="fv-row mb-7">
																		<!--begin::Label-->
																		<label class="required fw-bold fs-6 mb-2">Nombre</label>
																		<!--end::Label-->
																		<!--begin::Input-->
																		<input type="text" id="nombre" name="nombre" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Nombre" value="<?= esc(isset($categoria['NOMBRE']) ? $categoria['NOMBRE'] : set_value('nombre')) ?>" />
																		<!--end::Input-->
																	</div>
																	<!--end::Input group-->
																	<!--begin::Input group-->
																	<div class="fv-row mb-7">
																		<!--begin::Label-->
																		<label class="required fw-bold fs-6 mb-2">Descripción</label>
																		<!--end::Label-->
																		<!--begin::Input-->
																		<input type="text" id="descripcion" name="descripcion" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Descripción" value="<?= esc(isset($categoria['DESCRIPCION']) ? $categoria['DESCRIPCION'] : set_value('descripcion')) ?>"/>
																		<!--end::Input-->
																	</div>
																	<!--end::Input group-->
																</div>
																<!--end::Scroll-->
																<!--begin::Actions-->
																<div class="text-center pt-15">
																	<a href="<?= base_url('categoria') ?>" class="btn btn-light me-3">Cancelar</a>
																	<button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
																		<span class="indicator-label">Crear</span>
																		<span class="indicator-progress">Please wait...
																		<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
																	</button>
																</div>
																<!--end::Actions-->
															</form>
															<end::Form-->
														</div>
														<!--end::Modal body-->