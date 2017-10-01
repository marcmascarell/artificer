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

            <div v-if="field.type === 'radio'">
                <el-radio class="radio"
                          v-for="item in field.options.options"
                          :key="name"
                          v-model="values[name]"
                          :label="item.value">{{ item.label }}
                </el-radio>
            </div>

            <div v-if="field.type === 'checkbox'">
                <el-checkbox v-model="values[name]"></el-checkbox>
            </div>

            <div v-if="field.type === 'datetime'">
                <el-date-picker
                        :id="name"
                        v-model="values[name]"
                        type="datetime"
                        :picker-options="pickerWithShortcuts">
                </el-date-picker>
            </div>

            <div v-if="field.relation">
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
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
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
        props: ['fields', 'values']
    }
</script>

<style lang="scss" rel="stylesheet/scss">
</style>
