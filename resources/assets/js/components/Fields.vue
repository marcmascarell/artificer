<template>
    <div class="Fields box-body">
        <!--{{ form }}-->
        <!--<?= ($errors->has($field->getName())) ? 'has-error' : ''; ?>-->
        <div v-for="field, name in fields"
             :key="name"
             class="form-group">
            <label :for="name">
                {{ field.title }}
            </label>

            <!--{{field}}-->

            <el-input v-if="field.type === 'text'
                              || field.type === 'key'
                              || field.type === 'email'
                              || field.type === 'password'
                              || field.type === 'textarea'"
                      :id="name"
                      :type="field.type"
                      v-model="values[name]">
            </el-input>

            <div v-else-if="field.type === 'radio'">
                <el-radio class="radio"
                          v-for="item in field.options"
                          :key="name"
                          v-model="values[name]"
                          :label="item.value">{{ item.label }}
                </el-radio>
            </div>

            <div v-else-if="field.type === 'checkbox'">
                <el-checkbox v-model="values[name]"></el-checkbox>
            </div>

            <div v-else-if="field.type === 'datetime'">
                <el-date-picker
                        :id="name"
                        v-model="values[name]"
                        type="datetime"
                        :picker-options="pickerWithShortcuts">
                </el-date-picker>
            </div>

            <div v-else-if="field.type === 'image'">
                <!-- Important to keep `name` prop -->
                <el-upload
                        ref="upload"
                        action="http://artificer.dev/admin/model/test/upload"
                        :name="`${name}[]`"
                        :file-list="values[name]"
                        drag
                        multiple
                        list-type="picture-card"
                        :auto-upload="false">

                    <i class="el-icon-plus"></i>
                </el-upload>

            </div>

            <div v-else-if="field.relation">
                <el-select v-model="values[name]" :multiple="field.type === 'hasMany'" placeholder="Select">
                    <el-option
                            v-for="item in field.relation.options"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>

                <div v-if="false" class="text-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default">
                            <i class="fa fa-edit"></i>
                        </button>

                        <button type="button" class="btn btn-default">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <el-input v-else
                      :id="name"
                      type="text"
                      v-model="values[name]">
            </el-input>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                dialogImageUrl: '',
                dialogVisible: false,
                pickerWithShortcuts: {
                    shortcuts: [
                        {
                            text: 'Today',
                            onClick(picker) {
                                picker.$emit('pick', new Date());
                            }
                        },
                        {
                            text: 'Yesterday',
                            onClick(picker) {
                                const date = new Date();
                                date.setTime(date.getTime() - 3600 * 1000 * 24);
                                picker.$emit('pick', date);
                            }
                        },
                        {
                            text: 'A week ago',
                            onClick(picker) {
                                const date = new Date();
                                date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
                                picker.$emit('pick', date);
                            }
                        }
                    ]
                },
            }
        },
        props: ['fields', 'values'],
        methods: {
//            onImageSelect(file, fileList) {
//                console.log('file, fileList', file, fileList);
//
//                this.values['avatar'] = fileList;
//            },
            handleImageSuccess(res, file) {
                this.imageUrl = URL.createObjectURL(file.raw);
            },
            beforeImageUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG) {
                    this.$message.error('Avatar picture must be JPG format!');
                }
                if (!isLt2M) {
                    this.$message.error('Avatar picture size can not exceed 2MB!');
                }
                return isJPG && isLt2M;
            },
            handlePictureCardPreview(file) {
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            }
        }
    }
</script>

<style lang="scss" rel="stylesheet/scss">
    /*.avatar-uploader .el-upload {*/
        /*border: 1px dashed #d9d9d9;*/
        /*border-radius: 6px;*/
        /*cursor: pointer;*/
        /*position: relative;*/
        /*overflow: hidden;*/
    /*}*/
    /*.avatar-uploader .el-upload:hover {*/
        /*border-color: #20a0ff;*/
    /*}*/
    /*.avatar-uploader-icon {*/
        /*font-size: 28px;*/
        /*color: #8c939d;*/
        /*width: 178px;*/
        /*height: 178px;*/
        /*line-height: 178px;*/
        /*text-align: center;*/
    /*}*/
    /*.avatar {*/
        /*width: 178px;*/
        /*height: 178px;*/
        /*display: block;*/
    /*}*/

    .el-upload--picture-card {
        background-color: #fbfdff;
        border: 1px dashed #c0ccda;
        border-radius: 6px;
        box-sizing: border-box;
        width: 148px;
        height: 148px;
        cursor: pointer;
        line-height: 146px;
        vertical-align: top;
    }

    .el-upload-dragger {
        width: auto;
        height: auto;
    }

    .el-upload__input {
        display: none !important;
    }
</style>
