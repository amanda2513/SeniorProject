def enter_judge_registration_details
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

def verify_judge_added_to_table
  visit "http://gradproject.dynu.com/gerss/projects_judges"
  sleep 3
  p page.should have_content(@last_name)
  i = 1
    until find(:xpath, ".//table/tbody/tr[#{i}]/td[1]").text == @last_name
      i += 1
    end
  p i
  #else
  #raise "Judge Not added in to the judge tab"
end
