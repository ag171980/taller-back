function confirm(result) {
        if (result == 'yes') {
                store = Ext.data.StoreManager.lookup('UserGroupStore');
                store.remove(this);
                joinGroups();
        }
}

function joinGroups() {
        groupField = Ext.getCmp('groupField');
        store = Ext.data.StoreManager.lookup('UserGroupStore');
        groups = new Array();
        for (i in store.data.items) groups.push(store.data.items[i].data.idgroup);
        groupField.suspendEvent('change');
        groupField.setValue(groups.join(','));
        groupField.resumeEvent('change');
}

function renderGroups(groupField) {
        store = Ext.data.StoreManager.lookup('GroupStore');
        grid = Ext.getCmp('UserGroupGrid');
        grid.store.removeAll();
        if (groupField.value) {
                groups = groupField.getValue().split(',');
                for (i in groups) {
                        value =  new RegExp("^" + groups[i] + "$","i");
                        record = store.findRecord('idgroup', value);
                        if (record) {
                                //---make a copy of the original record
                                grid.store.add(record.copy());
                        }
                        //html+=record.data.name+'<br/>';
                }
        }

}
var UserRemove = Ext.create('Ext.Action', {
        formBind: true, //only enabled once the form is valid
        iconCls: 'icon-remove',
        text: 'Remove User',
        handler: function(widget, event) {
                var n = tree.getSelectionModel().getSelection()[0];
                Ext.MessageBox.show({
                        title: 'Remove User',
                        msg: 'What!?,you really want to remove this user?',
                        buttons: Ext.MessageBox.YESNO,
                        buttonText: {
                                yes: "Definitely!",
                                no: "Hell no!!!!"
                        },
                        fn: function(btn) {
                                if (btn == 'yes') {
                                        userform.getForm().reset();
                                        mygrid.store.remove(mygrid.selModel.selected.items[0]);
                                        mygrid.store.sync();
                                }
                        }
                });
        }
});
var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
var userform = Ext.create('Ext.form.Panel', {
        id: 'userForm',
        frame: false,
        autoScroll: true,
        bodyPadding: '5 5 0',
        fieldDefaults: {
                msgTarget: 'side',
                labelWidth: 80
        },
        defaults: {
                anchor: '100%'
        },
        actioncomplete: function() {
                renderGroups(Ext.getCmp('groupsContainer'));
        },
        items: [{
                        xtype: 'fieldset',
                        title: 'User Information',
                        defaultType: 'textfield',
                        collapsible: true,
                        layout: 'anchor',
                        defaults: {
                                anchor: '100%'
                        },
                        items: [{
                                        fieldLabel: 'IDU',
                                        name: 'idu',
                                        readOnly: true

                                }, {
                                        fieldLabel: 'First Name',
                                        afterLabelTextTpl: required,
                                        name: 'name',
                                        allowBlank: false
                                }, {
                                        fieldLabel: 'Last Name',
                                        afterLabelTextTpl: required,
                                        name: 'lastname',
                                        allowBlank: false
                                }, {
                                        fieldLabel: 'Company',
                                        name: 'company'
                                }, {
                                        fieldLabel: 'Email',
                                        afterLabelTextTpl: required,
                                        name: 'email',
                                        vtype: 'email'
                                }, {
                                        fieldLabel: 'ID Number',
                                        afterLabelTextTpl: required,
                                        name: 'idnumber'
                                }, {
                                        fieldLabel: 'Phone Number',
                                        name: 'phone'
                                }, {
                                        fieldLabel: 'Nick',
                                        name: 'nick'
                                }, {
                                        fieldLabel: 'Signature',
                                        name: 'signature',
                                        xtype: 'textareafield',
                                        grow: true
                                }, {
                                        xtype: 'textfield',
                                        name: 'passw',
                                        inputType: 'password',
                                        fieldLabel: 'Set Password'
                                }, {
                                        xtype: 'textfield',
                                        name: 'password2',
                                        fieldLabel: 'Repeat Password',
                                        submitValue: false,
                                        inputType: 'password',
                                        validator: function(value) {
                                                var password = this.previousSibling('[name=passw]');
                                                return (value === password.getValue()) ? true : 'Passwords do not match.'
                                        }
                                }

                        ]
                },
                /*{
                 xtype:'fieldset',
                 title: 'Phone Number',
                 collapsible: true,
                 defaultType: 'textfield',
                 layout: 'anchor',
                 defaults: {
                 anchor: '100%'
                 },
                 items :[{
                 fieldLabel: 'Home',
                 name: 'home'
                 },{
                 fieldLabel: 'Business',
                 name: 'business'
                 },{
                 fieldLabel: 'Mobile',
                 name: 'mobile'
                 },{
                 fieldLabel: 'Fax',
                 name: 'fax'
                 }]
                 }.*/
                {
                        xtype: 'fieldset',
                        title: 'Groups / Roles',
                        collapsible: true,
                        defaultType: 'textfield',
                        layout: 'anchor',
                        defaults: {
                                anchor: '100%'
                        },
                        items: [
                                ///----GroupField
                                {
                                        id: 'groupField',
                                        labelAlign: 'top',
                                        fieldLabel: 'Groups',
                                        hidden: false,
                                        name: 'group',
                                        listeners: {
                                                change: renderGroups
                                        }

                                }, {
                                        xtype: 'toolbar',
                                        items: [
                                                //---autocomplete from groups

                                                {
                                                        xtype: 'combo',
                                                        id: 'groupCombo',
                                                        store: Ext.data.StoreManager.lookup('GroupStore'),
                                                        displayField: 'name',
                                                        typeAhead: true,
                                                        hideLabel: true,
                                                        submitValue: false,
                                                        valueField: 'idgroup',
                                                        //hideTrigger:true,
                                                        anchor: '100%',
                                                        listConfig: {
                                                                loadingText: 'Searching...',
                                                                emptyText: 'No matching Groups found.'
                                                        }
                                                }, {
                                                        xtype: 'button',
                                                        text: 'Add',
                                                        icon: globals.module_url + 'assets/images/add.png',
                                                        handler: function() {
                                                                combo = Ext.getCmp('groupCombo');
                                                                if (combo.value) {
                                                                        groupField = Ext.getCmp('groupField');
                                                                        if (groupField.value) {
                                                                                groups = groupField.getValue().split(',');
                                                                        }
                                                                        else {
                                                                                groups = new Array();
                                                                        }
                                                                        if (groups.indexOf(combo.value.toString()) == -1) {
                                                                                groups.push(combo.value);
                                                                                groupField.setValue(groups.join(','));
                                                                        }
                                                                }
                                                        }
                                                }

                                        ]
                                },
                                //----container 4 grid
                                {
                                        xtype: 'container',
                                        items: [
                                                Ext.create('Ext.grid.Panel', {
                                                        id: 'UserGroupGrid',
                                                        store: Ext.data.StoreManager.lookup('UserGroupStore'),
                                                        title: 'Groups / Roles',
                                                        stateful: true,
                                                        collapsible: true,
                                                        multiSelect: true,
                                                        draggable: false,
                                                        stateId: 'stateGrid',
                                                        columns: [{
                                                                        menuDisabled: true,
                                                                        sortable: false,
                                                                        xtype: 'actioncolumn',
                                                                        width: 50,
                                                                        items: [{
                                                                                icon: globals.module_url + 'assets/images/delete.png', // Use a URL in the icon config
                                                                                tooltip: 'Remove user from group',
                                                                                handler: function(grid, rowIndex, colIndex) {
                                                                                        store = Ext.data.StoreManager.lookup('UserGroupStore');
                                                                                        var rec = store.getAt(rowIndex);
                                                                                        Ext.Msg.confirm('Confirm', 'Are you sure you want to remove: ' + rec.get('name') + '?', confirm, rec);
                                                                                }
                                                                        }]
                                                                }, {
                                                                        text: 'id',
                                                                        dataIndex: 'idgroup'
                                                                }, {
                                                                        text: 'name',
                                                                        flex: 1,
                                                                        dataIndex: 'name'
                                                                }] //---end columns
                                                                ,
                                                        viewConfig: {
                                                                draggable: false,
                                                                plugins: {
                                                                        ptype: 'gridviewdragdrop',
                                                                        ddGroup: 'user',
                                                                        copy: true
                                                                },
                                                                listeners: {
                                                                        beforedrop: function(node, data, overModel, position, dropFunction, options) {

                                                                        },
                                                                        drop: function(node, data, dropRec, dropPosition) {
                                                                                joinGroups();
                                                                                //return true;
                                                                                //
                                                                                //console.log(data.records[0].data);
                                                                                //var dropOn = dropRec ? ' ' + dropPosition + ' ' + dropRec.get('title') : ' on empty view';
                                                                                //console.log("Drag", 'Dropped ' + data.records[0].get('title') +'\n'+ dropOn);
                                                                        }
                                                                } //---listeners
                                                        }
                                                })
                                        ]
                                }
                                //----container 4 grid


                        ]
                } //---end fieldset    
        ], //----end form items

        buttons: [
                /*
                 {
                 text: '<i class="icon-remove"></i> Delete',
                 formBind: true, //only enabled once the form is valid
                 handler: function() {
                 this.up('form').getForm().reset();
                 mygrid.store.remove(mygrid.selModel.selected.items[0]);
                 mygrid.store.sync();
                 }
                 }*/
                UserRemove, {
                        text: '<i class="icon-beaker"></i> Test',
                        formBind: true, //only enabled once the form is valid
                        handler: function() {
                                form = this.up('form').getForm();
                                val = form.getValues()
                                window.location = globals.module_url + 'admin/test_user/' + val.idu;
                        }
                }, {
                        text: '<i class="icon-refresh"></i> Reset',
                        handler: function() {
                                this.up('form').getForm().reset();
                        }
                }, {
                        text: '<i class="icon-save"></i> Save',
                        formBind: true, //only enabled once the form is valid
                        disabled: true,
                        handler: function() {
                                var form = this.up('form').getForm();
                                //---update grid
                                data = Ext.getCmp('userForm').form.getFieldValues();
                                record = mygrid.store.findRecord('idu', data.idu);
                                if (record)
                                        record.set(data);
                                if (form.isValid()) {
                                        form.submit({
                                                success: function(form, action) {
                                                        this.form.setValues(action.result.data)
                                                        Ext.Msg.alert('Success', action.result.msg);
                                                },
                                                failure: function(form, action) {
                                                        Ext.Msg.alert('Failed', action.result.msg);
                                                }
                                        });
                                }
                        }
                }
        ],
        url: globals.module_url + 'admin/user/update'
});
