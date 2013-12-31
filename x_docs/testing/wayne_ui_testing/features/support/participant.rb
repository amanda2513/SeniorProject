def verify_user_is_on_participant_page
  find(:id, 'btn_add_participant')
end

def verify_user_is_on_score_entry_user_page
  visit "http://gradproject.dynu.com/manage_users/seu"
  find(:id, 'btn_add_seu')
end

def verify_user_is_on_admin_page
  visit "http://gradproject.dynu.com/manage_users/admin"
  find(:id, 'btn_add_admin')
end

def verify_user_is_on_judge_page
  visit "http://gradproject.dynu.com/gerss/projects_judges"
  p find(:xpath, '//div[3]/ul/li[2]/a').value
  find(:id, 'btn_add_judge')
end

def click_on_button_add_judge
  find(:id, 'btn_add_judge').click
end

def click_on_button_add_user
  find(:id, 'btn_add_judge').click
end


def click_on_button_add_participant
  find(:id, 'btn_add_judge').click
end

def click_on_button_add_score_entry_user
  find(:id, 'btn_add_seu').click
  sleep 3
end

def click_on_button_add_admin
  find(:id, 'btn_add_admin').click
  sleep 3
end

def verify_user_type_when_add_judge
  p find_field('type').find('option[selected]').text
end


def verify_judge_details_in_edit_page
  find_field('type').find('option[selected]').text == "Judge"
  find(:xpath, ".//*[@id='registration']/div/div/div[1]/div/div[1]/div/input[1]").value == @first_name
  find(:xpath, ".//*[@id='registration']/div/div/div[1]/div/div[1]/div/input[2]").value == @last_name
  find(:xpath, ".//*[@id='email']").value == @email
  find_button('Update User Info')

end
#def click_on_sort_participant
#  if find(:class, 'headerSortDown')
#    find(:class, 'headerSortUp')
#  end
#end

def verify_list_of_user_registration_type_contains(user_type1,user_type2,user_type3,user_type4)
  list = find(:id, 'value').value
  p list
  #p list.class
end

def get_number_of_participant_rows
  @num_of_rows =  page.all('#project_participants_table tr').size
  p @num_of_rows
end

def get_num_of_rows_after_adding_participant
  visit "http://gradproject.dynu.com/gerss/projects_participants"
  sleep 4
  @num_of_rows_after_adding =  page.all('#project_participants_table tr').size
  p @num_of_rows_after_adding
  @num_of_rows_after_adding == (@num_of_rows + 1)

end


def get_number_of_participant_rows_judge
  page.all('#project_judges_table tr').size
end

def modify_first_name_judge_details
  fill_in('firstname', :with => "MODIFY_EDIT_Judge")
end

def get_num_of_rows_after_adding_judge(rows_before_judge_added)
  visit "http://gradproject.dynu.com/gerss/projects_judges"
  sleep 4
  @num_of_rows_after_adding_judge =  page.all('#project_judges_table tr').size
  p @num_of_rows_after_adding_judge
  @num_of_rows_after_adding == (rows_before_judge_added + 1)
end

def verify_user_added_to_the_list
  page.should have_content('User Added')
  get_num_of_rows_after_adding_participant
  #all('table tr').each { find(:xpath, "//td[1]").text }
  i = 1
  until find(:xpath, "//table/tbody/tr[#{i}]/td[1]").text == @last_name
    i += 1
  end
  p i
  #find(:xpath, "//tr[#{i}/td[7]/a//i").click
end

def search_result_has_user_searched
  @num_of_rows_after_search =  page.all('#project_participants_table tr').size
  if [ @num_of_rows_after_search >= 1 ] then
  i = 1
    until find(:xpath, "//table/tbody/tr[#{i}]/td[1]").text == @last_name
      i += 1
    end
    p i
  else
    raise "No Search Result Found"
  end
end

def search_for_user_last_name
  #find(:id, "search").set(@last_name)
  p @last_name
  sleep 2
  find(:xpath,"//*[@class='input-append']/input").set("EDIT")
  sleep 1
  find(:xpath,"//*[@class='input-append']/button").click
  sleep 2
end

def click_on_the_edit_user_icon
  visit "http://gradproject.dynu.com/gerss/projects_participants"
  sleep 5
  i = 1
  until find(:xpath, "//table/tbody/tr[#{i}]/td[1]").text == @last_name
    i += 1
  end
  p i
  find(:xpath, "//tr[#{i}]/td[7]/a//i").click
end

def click_on_modify_judge_icon
  sleep 2
  j = 1
  until find(:xpath, "//table/tbody/tr[#{j}]/td[1]").text == @last_name
    j += 1
  end
  p j
  find(:xpath, "//tr[#{j}]/td[5]/a//i").click
end


def click_on_modify_judge_icon_on_manage_user_page
  sleep 2
  j = 1
  until find(:xpath, "//table/tbody/tr[#{j}]/td[1]").text == @last_name
    j += 1
  end
  p j
  find(:xpath, "//*[@id='users_judge_table']/tbody/tr[#{j}]/td[4]/a[2]/i").click
  sleep 4
end

def verify_user_on_the_modification_page
  find(:id, 'register_btn').value == 'Update User Info'
end


def verify_user_added_to_the_judge_list
  page.should have_content('User Added')
  @rows_deforre_judge_added = get_number_of_participant_rows_judge
  p @rows_deforre_judge_added
  get_num_of_rows_after_adding_judge(@rows_deforre_judge_added)
  #all('table tr').each { find(:xpath, "//td[1]").text }
  i = 1
  until find(:xpath, "//table/tbody/tr[#{i}]/td[1]").text == @last_name
    i += 1
  end
end

def verify_judge_table_for_edited_first_name
  visit "http://gradproject.dynu.com/gerss/projects_judges"
  sleep 3
  i = 1
  until find(:xpath, "//table/tbody/tr[#{i}]/td[2]").text == "MODIFY_EDIT_Judge"
    i += 1
  end
end

def verify_error_with_required_message(email)
  page.should have_content("The #{email} field is required.")
end

def edit_user_info_first_name
  #fill_in('First name', :with =>  "EDITTEST")
  find(:xpath, ".//*[@id='basic_info_span']/div[1]/div/input[1]").set("TESTEDIT")
  find_button("Update User Info").click
end

def verify_user_info_has_updated
  page.should have_content("User Info Has Been Updated")
  sleep 2
  visit "http://gradproject.dynu.com/gerss/projects_participants"
  sleep 2
  page.should have_content("TESTEDIT")
end


def enter_user_participant_details
  @user_details = UserDetails.new
  @email = @user_details.get_email
  @first_name = @user_details.get_first_name
  @last_name = @user_details.get_last_name
  p @email
  p @first_name
  p @last_name
  fill_in('firstname', :with => @first_name)
  fill_in('lastname', :with => @last_name)
  select('Computer Science', :from => 'department')
  fill_in('email', :with => @email)
  fill_in('password', :with => "test1234")
  fill_in('cpassword', :with => "test1234")
  fill_in('project_title', :with => "test project for wst")
  fill_in('project_desc', :with => "test project description of #{@first_name}")
  find_button("Add User").click
  sleep 5
end


def enter_score_entry_user_participant_details
  select('Score Entry User', :from => 'type')

  @user_details = UserDetails.new
  @email = @user_details.get_email
  @first_name = @user_details.get_first_name
  @last_name = @user_details.get_last_name
  p @email
  p @first_name
  p @last_name
  fill_in('firstname', :with => @first_name)
  fill_in('lastname', :with => @last_name)
  select('English', :from => 'department')
  fill_in('email', :with => @email)
  fill_in('password', :with => "test1234")
  fill_in('cpassword', :with => "test1234")
  find_button("Add User").click
  sleep 5

end

def enter_admin_participant_details
  select('Admin', :from => 'type')

  @user_details = UserDetails.new
  @email = @user_details.get_email
  @first_name = @user_details.get_first_name
  @last_name = @user_details.get_last_name
  p @email
  p @first_name
  p @last_name
  fill_in('firstname', :with => @first_name)
  fill_in('lastname', :with => @last_name)
  select('English', :from => 'department')
  fill_in('email', :with => @email)
  fill_in('password', :with => "test1234")
  fill_in('cpassword', :with => "test1234")
  find_button("Add User").click
  sleep 5
end

def verify_user_added_to_admin_table
  visit "http://gradproject.dynu.com/manage_users/admin"
  sleep 3
  j = 1
  until find(:xpath, "//table/tbody/tr[#{j}]/td[1]").text == @last_name
    j += 1
  end
  p j
end

def verify_user_added_to_score_entry_user_table
  visit "http://gradproject.dynu.com/manage_users/seu"
  sleep 3
  j = 1
  until find(:xpath, "//table/tbody/tr[#{j}]/td[1]").text == @last_name
    j += 1
  end
  p j

end

def enter_user_participant_details_for_registration
  @user_details = UserDetails.new

  @first_name = @user_details.get_first_name
  @last_name = @user_details.get_last_name
  @email = @last_name + "@" + "wayne.edu"
  p @first_name
  p @last_name
  fill_in('firstname', :with => @first_name)
  fill_in('lastname', :with => @last_name)
  select('Computer Science', :from => 'department')
  find(:xpath, "//form[@id='sign_in_form']/input[1]").set(@email)
  sleep 2
  find(:xpath, "//form[@id='sign_in_form']/input[2]").set("test1234")
  sleep 2
  find(:xpath, "//form[@id='sign_in_form']/input[3]").set("test1234")
  sleep 4
  #fill_in('password', :with => "test1234")
  #fill_in('cpassword', :with => "test1234")
  fill_in('project_title', :with => "test project for wst")
  fill_in('project_desc', :with => "test project description of #{@first_name}")
  find_button("Register").click
  sleep 5


end

def enter_user_judge_details_all
  select('Judge', :from => 'type')

  @user_details = UserDetails.new
  @email = @user_details.get_email
  @first_name = @user_details.get_first_name
  @last_name = @user_details.get_last_name
  p @email
  p @first_name
  p @last_name
  fill_in('firstname', :with => @first_name)
  fill_in('lastname', :with => @last_name)
  select('Computer Science', :from => 'department')
  fill_in('email', :with => @email)
  fill_in('password', :with => "test1234")
  fill_in('cpassword', :with => "test1234")
  find_button("Add User").click
  sleep 5
end

def enter_user_participant_details_without_email
  @user_details = UserDetails.new
  @email = @user_details.get_email
  @first_name = @user_details.get_first_name
  @last_name = @user_details.get_last_name
  p @email
  p @first_name
  p @last_name
  fill_in('firstname', :with => @first_name)
  fill_in('lastname', :with => @last_name)
  select('Computer Science', :from => 'department')
  #fill_in('email', :with => @email)
  fill_in('password', :with => "test1234")
  fill_in('cpassword', :with => "test1234")
  fill_in('project_title', :with => "test project for wst")
  fill_in('project_desc', :with => "test project description of #{@first_name}")
  find_button("Add User").click
  sleep 5
end

def enter_user_participant_details_without_optional_parameters
  @user_details = UserDetails.new
  @email = @user_details.get_email
  @first_name = @user_details.get_first_name
  @last_name = @user_details.get_last_name
  p @email
  p @first_name
  p @last_name
  fill_in('firstname', :with => @first_name)
  fill_in('lastname', :with => @last_name)
  select('Computer Science', :from => 'department')
  fill_in('email', :with => @email)
  fill_in('password', :with => "test1234")
  fill_in('cpassword', :with => "test1234")
  #fill_in('project_title', :with => "test project for wst")
  #fill_in('project_desc', :with => "test project description of #{@first_name}")
  find_button("Add User").click
end





#def from_table(rule_table)
#  rule_table.hashes.each do | row |
#    @max_devices = row['max_devices'] unless row['max_devices'] == nil
#    @device_change_period_interval = row['device_change_period_interval']  unless row['device_change_period_interval'] == nil
#    @device_change_period_quantity = row['device_change_period_quantity']  unless row['device_change_period_quantity'] == nil
#    @allowed_device_changes_per_period = row['allowed_device_changes_per_period']  unless row['allowed_device_changes_per_period'] == nil
#    @max_active_devices  = row['max_active_devices']  unless row['max_active_devices'] == nil
#    @heartbeat_frequency_interval = row['heartbeat_frequency_interval']  unless row['heartbeat_frequency_interval'] == nil
#    @heartbeat_frequency_quantity = row['heartbeat_frequency_quantity']  unless row['heartbeat_frequency_quantity'] == nil
#    @allowed_missed_heartbeats = row['allowed_missed_heartbeats'] unless row['allowed_missed_heartbeats'] == nil
#    @expiry_date = row['expiry_date'] unless row['expiry_date'].nil?
#    allowed_countries(row['allowed_countries']) unless row['allowed_countries'] == nil
#    vpn_restriction_levels(row['vpn_restriction_levels']) unless row['vpn_restriction_levels'] == nil
#    blocked_platforms(row['blocked_platforms'])unless row['blocked_platforms'] == nil
#  end
#
#end